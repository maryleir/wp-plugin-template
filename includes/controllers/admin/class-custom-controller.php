<?php
namespace Your_MVC_Plugin\Controllers\Admin;

use Your_MVC_Plugin\Controllers\Base_Controller;
use Your_MVC_Plugin\I18n;

/**
 * Custom Admin Controller
 * 
 * Handles the custom admin page display and functionality
 * 
 * @since      1.0.0
 */
class Custom_Controller extends Base_Controller {
    
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
        parent::__construct(null);
    }
    
    /**
     * Register controller hooks.
     *
     * @since    1.0.0
     */
    protected function register_hooks() {
        // This controller doesn't register hooks directly
    }
    
    /**
     * Display the custom page.
     *
     * @since    1.0.0
     * @return   string    The rendered page content.
     */
    public function display() {
        // Process form submission if needed
        $this->process_form_submission();
        
        // Get custom data
        $data = $this->get_custom_data();
        
        // Create the view
        $view = $this->create_view($data);
        
        // Return the rendered view
        return $view->render();
    }
    
    /**
     * Process custom form submission.
     *
     * @since    1.0.0
     */
    private function process_form_submission() {
        // Check if form was submitted
        if (!isset($_POST['your_mvc_plugin_custom_submit'])) {
            return;
        }
        
        // Verify nonce
        if (!isset($_POST['your_mvc_plugin_custom_nonce']) || 
            !wp_verify_nonce($_POST['your_mvc_plugin_custom_nonce'], 'your_mvc_plugin_custom_save')) {
            add_custom_error(
                'your_mvc_plugin_custom',
                'nonce_error',
                I18n::translate('Security check failed.'),
                'error'
            );
            return;
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            add_custom_error(
                'your_mvc_plugin_custom',
                'permission_error',
                I18n::translate('You do not have permission to change custom.'),
                'error'
            );
            return;
        }
        
        // Process and sanitize custom
        $custom = array();
        
        // Text field
        if (isset($_POST['general_text_field'])) {
            $custom['general_text_field'] = sanitize_text_field($_POST['general_text_field']);
        }
        
        // Checkbox
        $custom['general_checkbox'] = isset($_POST['general_checkbox']) ? 1 : 0;
        
        // Select field
        if (isset($_POST['general_select'])) {
            $valid_options = array('option1', 'option2', 'option3');
            if (in_array($_POST['general_select'], $valid_options)) {
                $custom['general_select'] = $_POST['general_select'];
            } else {
                $custom['general_select'] = 'option1'; // Default
                add_custom_error(
                    'your_mvc_plugin_custom',
                    'invalid_select',
                    I18n::translate('Invalid option selected.'),
                    'error'
                );
            }
        }
        
        // Textarea
        if (isset($_POST['general_textarea'])) {
            $custom['general_textarea'] = sanitize_textarea_field($_POST['general_textarea']);
        }
        
        // Save custom
        update_option('your_mvc_plugin_custom', $custom);
        
        // Add success message
        add_custom_error(
            'your_mvc_plugin_custom',
            'custom_updated',
            I18n::translate('Custom saved successfully.'),
            'success'
        );
    }
    
    /**
     * Get custom data for the view.
     *
     * @since    1.0.0
     * @return   array    Settings data.
     */
    private function get_custom_data() {
        // Get current custom
        $custom = get_option('your_mvc_plugin_custom', array());
        
        // Default values
        $defaults = array(
            'general_text_field' => '',
            'general_checkbox' => 0,
            'general_select' => 'option1',
            'general_textarea' => '',
        );
        
        // Merge with defaults
        $custom = wp_parse_args($custom, $defaults);
        
        return array(
            'plugin_name' => I18n::translate('Your MVC Plugin'),
            'custom' => $custom,
            'nonce' => wp_create_nonce('your_mvc_plugin_custom_save'),
        );
    }
    
    /**
     * Create the view instance.
     *
     * @since    1.0.0
     * @param    array    $data    Data for the view.
     * @return   \Your_MVC_Plugin\Views\Admin\Custom_View    The view instance.
     */
    private function create_view($data) {
        // Load the view class if it doesn't use autoloading
        require_once YOUR_PLUGIN_PATH . 'includes/views/admin/class-custom-view.php';
        
        // Create and return the view instance
        return new \Your_MVC_Plugin\Views\Admin\Custom_View($data);
    }
}