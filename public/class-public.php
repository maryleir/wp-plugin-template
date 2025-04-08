<?php
namespace Your_MVC_Plugin\Frontend;

use Your_MVC_Plugin\I18n;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for the public-facing side.
 *
 * @since      1.0.0
 */
class Public_Frontend {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->plugin_name = 'your-mvc-plugin';
        $this->version = YOUR_PLUGIN_VERSION;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            YOUR_PLUGIN_URL . 'public/css/public.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            YOUR_PLUGIN_URL . 'public/js/public.js',
            array('jquery'),
            $this->version,
            true
        );

        // Localize the script with new data
        wp_localize_script(
            $this->plugin_name,
            'your_mvc_plugin_public',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('your_mvc_plugin_public_nonce'),
                'site_url' => site_url('/'),
                'i18n' => array(
                    'loading' => I18n::translate('Loading...'),
                    'error' => I18n::translate('An error occurred. Please try again.'),
                    'success' => I18n::translate('Success!'),
                ),
            )
        );
    }

    /**
     * Register shortcodes.
     *
     * @since    1.0.0
     */
    public function register_shortcodes() {
        add_shortcode('your_mvc_plugin', array($this, 'render_main_shortcode'));
        add_shortcode('your_mvc_plugin_feature', array($this, 'render_feature_shortcode'));
    }

    /**
     * Render the main shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            Shortcode output.
     */
    public function render_main_shortcode($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(
            array(
                'id' => '',
                'class' => '',
                'title' => I18n::translate('Default Title'),
                'display' => 'default',
            ),
            $atts,
            'your_mvc_plugin'
        );

        // Create controller instance
        $controller = new \Your_MVC_Plugin\Controllers\Frontend\Main_Controller();
        
        // Pass attributes to controller and get rendered content
        return $controller->render_shortcode($atts);
    }

    /**
     * Render the feature shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            Shortcode output.
     */
    public function render_feature_shortcode($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(
            array(
                'id' => '',
                'feature' => 'default',
                'style' => 'standard',
            ),
            $atts,
            'your_mvc_plugin_feature'
        );

        // Create controller instance
        $controller = new \Your_MVC_Plugin\Controllers\Frontend\Feature_Controller();
        
        // Pass attributes to controller and get rendered content
        return $controller->render_shortcode($atts);
    }

    /**
     * Register AJAX handlers for public-facing functionality.
     *
     * @since    1.0.0
     */
    public function register_ajax_handlers() {
        // For logged-in users
        add_action('wp_ajax_your_mvc_plugin_public_action', array($this, 'handle_ajax_request'));
        
        // For non-logged-in users
        add_action('wp_ajax_nopriv_your_mvc_plugin_public_action', array($this, 'handle_ajax_request'));
    }

    /**
     * Handle AJAX requests from public-facing pages.
     *
     * @since    1.0.0
     */
    public function handle_ajax_request() {
        // Check security nonce
        if (!check_ajax_referer('your_mvc_plugin_public_nonce', 'nonce', false)) {
            wp_send_json_error(array('message' => I18n::translate('Security check failed.')));
        }
        
        // Process the AJAX request
        $action = isset($_POST['custom_action']) ? sanitize_text_field($_POST['custom_action']) : '';
        
        switch ($action) {
            case 'get_data':
                // Example of retrieving data
                $data = array(
                    'timestamp' => current_time('timestamp'),
                    'sample_data' => array(
                        'key1' => 'value1',
                        'key2' => 'value2'
                    )
                );
                
                wp_send_json_success(array(
                    'message' => I18n::translate('Data retrieved successfully!'),
                    'data' => $data
                ));
                break;
                
            case 'submit_form':
                // Process form submission
                $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
                $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
                $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
                
                // Validate inputs
                $errors = array();
                
                if (empty($name)) {
                    $errors['name'] = I18n::translate('Name is required.');
                }
                
                if (empty($email) || !is_email($email)) {
                    $errors['email'] = I18n::translate('A valid email is required.');
                }
                
                if (empty($message)) {
                    $errors['message'] = I18n::translate('Message is required.');
                }
                
                // If there are errors, return them
                if (!empty($errors)) {
                    wp_send_json_error(array(
                        'message' => I18n::translate('Please correct the errors and try again.'),
                        'errors' => $errors
                    ));
                }
                
                // Process the form data (example: store in database)
                $submission_id = $this->save_form_submission($name, $email, $message);
                
                if (!$submission_id) {
                    wp_send_json_error(array('message' => I18n::translate('Error saving your submission. Please try again.')));
                }
                
                wp_send_json_success(array(
                    'message' => I18n::translate('Thank you for your submission!'),
                    'submission_id' => $submission_id
                ));
                break;
                
            default:
                wp_send_json_error(array('message' => I18n::translate('Invalid action.')));
                break;
        }
    }

    /**
     * Save form submission to database.
     *
     * @since    1.0.0
     * @param    string    $name       The name from the form.
     * @param    string    $email      The email from the form.
     * @param    string    $message    The message from the form.
     * @return   int|false             The submission ID on success, false on failure.
     */
    private function save_form_submission($name, $email, $message) {
        global $wpdb;
        
        // Example: Save to a custom database table
        $table_name = $wpdb->prefix . 'your_mvc_plugin_submissions';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'message' => $message,
                'created_at' => current_time('mysql'),
                'ip_address' => $this->get_client_ip(),
            ),
            array(
                '%s', // name
                '%s', // email
                '%s', // message
                '%s', // created_at
                '%s', // ip_address
            )
        );
        
        if ($result === false) {
            return false;
        }
        
        return $wpdb->insert_id;
    }

    /**
     * Get client IP address.
     *
     * @since    1.0.0
     * @return   string    The client IP address.
     */
    private function get_client_ip() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP from shared internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            // IP from remote address
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return sanitize_text_field($ip);
    }

    /**
     * Register custom rewrites rules.
     *
     * @since    1.0.0
     */
    public function register_rewrites() {
        add_rewrite_rule(
            '^your-mvc-plugin/([^/]+)/?$',
            'index.php?your_mvc_plugin_item=$matches[1]',
            'top'
        );
    }

    /**
     * Register custom query variables.
     *
     * @since    1.0.0
     * @param    array    $query_vars    The query variables.
     * @return   array                   Modified query variables.
     */
    public function register_query_vars($query_vars) {
        $query_vars[] = 'your_mvc_plugin_item';
        return $query_vars;
    }

    /**
     * Handle custom templates for plugin pages.
     *
     * @since    1.0.0
     * @param    string    $template    The template file.
     * @return   string                 The modified template file.
     */
    public function handle_template($template) {
        // Check if we're on a custom plugin page
        $item = get_query_var('your_mvc_plugin_item');
        
        if (!empty($item)) {
            // Create the controller
            $controller = new \Your_MVC_Plugin\Controllers\Frontend\Page_Controller();
            
            // Let the controller handle the template
            $custom_template = $controller->get_template($item);
            
            if (!empty($custom_template) && file_exists($custom_template)) {
                return $custom_template;
            }
        }
        
        return $template;
    }
    
    /**
     * Register custom widgets.
     *
     * @since    1.0.0
     */
    public function register_widgets() {
        register_widget('\Your_MVC_Plugin\Widgets\Main_Widget');
    }
    
    /**
     * Add custom classes to the body.
     *
     * @since    1.0.0
     * @param    array    $classes    The body classes.
     * @return   array                Modified body classes.
     */
    public function add_body_classes($classes) {
        // Add a class to all pages
        $classes[] = 'your-mvc-plugin';
        
        // Check if we're on a custom plugin page
        $item = get_query_var('your_mvc_plugin_item');
        if (!empty($item)) {
            $classes[] = 'your-mvc-plugin-page';
            $classes[] = 'your-mvc-plugin-' . sanitize_html_class($item);
        }
        
        return $classes;
    }
}