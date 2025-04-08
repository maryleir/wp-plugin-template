<?php
namespace Your_MVC_Plugin\Controllers;

use Your_MVC_Plugin\Auth\JWT_Auth_Handler;

/**
 * API Controller
 * 
 * Handles custom REST API routes with JWT authentication
 * 
 * @since      1.0.0
 */
class API_Controller extends Base_Controller {
    
    /**
     * API namespace
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $namespace    The API namespace
     */
    protected $namespace = 'your-mvc-plugin/v1';
    
    /**
     * Register controller hooks
     *
     * @since    1.0.0
     */
    protected function register_hooks() {
        $this->loader->add_action('rest_api_init', $this, 'register_routes');
    }
    
    /**
     * Register REST API routes
     *
     * @since    1.0.0
     */
    public function register_routes() {
        // Public endpoint (no authentication required)
        register_rest_route($this->namespace, '/public', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_public_data'),
            'permission_callback' => '__return_true',
        ));
        
        // Protected endpoint (authentication required)
        register_rest_route($this->namespace, '/protected', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_protected_data'),
            'permission_callback' => array($this, 'check_authentication'),
        ));
        
        // Admin-only endpoint (admin authentication required)
        register_rest_route($this->namespace, '/admin', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_admin_data'),
            'permission_callback' => array($this, 'check_admin_authentication'),
        ));
        
        // User profile endpoint (authentication required)
        register_rest_route($this->namespace, '/user/profile', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_user_profile'),
            'permission_callback' => array($this, 'check_authentication'),
        ));
    }
    
    /**
     * Check if user is authenticated via JWT
     *
     * @since    1.0.0
     * @return   boolean|WP_Error    True if authenticated, WP_Error on failure
     */
    public function check_authentication() {
        $auth = JWT_Auth_Handler::get_instance();
        return $auth->validate_token(true);
    }
    
    /**
     * Check if user is authenticated and has admin role
     *
     * @since    1.0.0
     * @return   boolean|WP_Error    True if authenticated and admin, WP_Error on failure
     */
    public function check_admin_authentication() {
        $auth = JWT_Auth_Handler::get_instance();
        
        // Validate the token first
        $validated = $auth->validate_token(true);
        
        if (is_wp_error($validated)) {
            return $validated;
        }
        
        // Check if user has admin role
        if (!$auth->user_has_role('administrator')) {
            return new \WP_Error(
                'jwt_auth_unauthorized',
                'You do not have permission to access this resource',
                array('status' => 403)
            );
        }
        
        return true;
    }
    
    /**
     * Public endpoint callback
     *
     * @since    1.0.0
     * @return   WP_REST_Response    The REST response
     */
    public function get_public_data() {
        $data = array(
            'success' => true,
            'message' => 'This is public data that anyone can access',
            'data' => array(
                'timestamp' => current_time('timestamp'),
                'version' => YOUR_PLUGIN_VERSION,
            ),
        );
        
        return rest_ensure_response($data);
    }
    
    /**
     * Protected endpoint callback
     *
     * @since    1.0.0
     * @return   WP_REST_Response    The REST response
     */
    public function get_protected_data() {
        $auth = JWT_Auth_Handler::get_instance();
        $user_data = $auth->get_user_data();
        
        $data = array(
            'success' => true,
            'message' => 'This is protected data that only authenticated users can access',
            'data' => array(
                'timestamp' => current_time('timestamp'),
                'user' => array(
                    'id' => $user_data['user']['id'],
                    'role' => $user_data['user']['role'],
                ),
            ),
        );
        
        return rest_ensure_response($data);
    }
    
    /**
     * Admin endpoint callback
     *
     * @since    1.0.0
     * @return   WP_REST_Response    The REST response
     */
    public function get_admin_data() {
        $auth = JWT_Auth_Handler::get_instance();
        $user_data = $auth->get_user_data();
        
        // Get some admin-only stats
        $users_count = count_users();
        $posts_count = wp_count_posts();
        
        $data = array(
            'success' => true,
            'message' => 'This is admin-only data',
            'data' => array(
                'timestamp' => current_time('timestamp'),
                'user' => array(
                    'id' => $user_data['user']['id'],
                    'role' => $user_data['user']['role'],
                ),
                'stats' => array(
                    'users' => $users_count['total_users'],
                    'posts' => $posts_count->publish,
                ),
            ),
        );
        
        return rest_ensure_response($data);
    }
    
    /**
     * User profile endpoint callback
     *
     * @since    1.0.0
     * @return   WP_REST_Response    The REST response
     */
    public function get_user_profile() {
        $auth = JWT_Auth_Handler::get_instance();
        $user = $auth->get_current_user();
        
        // Get user metadata
        $first_name = get_user_meta($user->ID, 'first_name', true);
        $last_name = get_user_meta($user->ID, 'last_name', true);
        $description = get_user_meta($user->ID, 'description', true);
        
        $data = array(
            'success' => true,
            'message' => 'User profile data',
            'data' => array(
                'timestamp' => current_time('timestamp'),
                'user' => array(
                    'id' => $user->ID,
                    'email' => $user->user_email,
                    'username' => $user->user_login,
                    'display_name' => $user->display_name,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'bio' => $description,
                    'registered' => $user->user_registered,
                    'role' => $user->roles[0] ?? 'subscriber',
                ),
            ),
        );
        
        return rest_ensure_response($data);
    }
}