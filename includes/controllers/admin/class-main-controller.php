<?php
namespace Your_MVC_Plugin\Controllers\Admin;

use Your_MVC_Plugin\Controllers\Base_Controller;
use Your_MVC_Plugin\I18n;


/**
 * Main Admin Controller
 * 
 * Handles the main admin page display and functionality
 * 
 * @since      1.0.0
 */
class Main_Controller extends Base_Controller {
    
    /**
     * The admin instance.
     *
     * @since    1.0.0
     * @access   protected
     * @var      \Your_MVC_Plugin\Admin    $admin    The admin instance.
     */
    protected $admin;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    \Your_MVC_Plugin\Admin    $admin    The admin instance.
     */
    public function __construct($admin) {
        $this->admin = $admin;
        
        // Base controller requires a loader, but we're not using hooks in this case
        // You can pass null or implement a method to get the loader from admin
        parent::__construct(null);
    }
    
    /**
     * Register controller hooks.
     *
     * @since    1.0.0
     */
    protected function register_hooks() {
        // This controller doesn't register hooks directly
        // Hooks are registered in the main Admin class
    }
    
    /**
     * Display the main admin page.
     *
     * @since    1.0.0
     * @return   string    The rendered page content.
     */
    public function display() {
        // Get data for the view
        $data = $this->get_dashboard_data();
        
        // Create the view
        $view = $this->create_view($data);
        
        // Return the rendered view
        return $view->render();
    }
    
    /**
     * Get dashboard data for the view.
     *
     * @since    1.0.0
     * @return   array    Data for the dashboard view.
     */
    private function get_dashboard_data() {
        // Get plugin settings
        $settings = get_option('your_mvc_plugin_settings', array());
        
        // Get some example stats
        $stats = array(
            'total_posts' => wp_count_posts()->publish,
            'total_pages' => wp_count_posts('page')->publish,
            'total_users' => count_users()['total_users'],
            'current_time' => current_time('mysql'),
        );
        
        // Recent posts
        $recent_posts = get_posts(array(
            'numberposts' => 5,
            'post_status' => 'publish'
        ));
        
        return array(
            'plugin_name' => I18n::translate('Your MVC Plugin'),
            'version' => YOUR_PLUGIN_VERSION,
            'settings' => $settings,
            'stats' => $stats,
            'recent_posts' => $recent_posts,
        );
    }
    
    /**
     * Create the view instance.
     *
     * @since    1.0.0
     * @param    array    $data    Data for the view.
     * @return   \Your_MVC_Plugin\Views\Admin\Main_View    The view instance.
     */
    private function create_view($data) {
        // Load the view class if it doesn't use autoloading
        require_once YOUR_PLUGIN_PATH . 'includes/views/admin/class-main-view.php';
        
        // Create and return the view instance
        return new \Your_MVC_Plugin\Views\Admin\Main_View($data);
    }
}