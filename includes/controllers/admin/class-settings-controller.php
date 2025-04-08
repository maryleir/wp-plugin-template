<?php
namespace Your_MVC_Plugin\Controllers\Admin;

use Your_MVC_Plugin\Controllers\Base_Controller;
use Your_MVC_Plugin\I18n;

/**
 * Settings Admin Controller
 * 
 * Handles the settings admin page display and functionality
 * 
 * @since      1.0.0
 */
class Settings_Controller extends Base_Controller {
    
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
     * Display the settings page.
     *
     * @since    1.0.0
     * @return   string    The rendered page content.
     */
    public function display() {
        // Process form submission if needed
        $this->process_form_submission();
        
        // Get settings data
        $data = $this->get_settings_data();
        
        // Create the view
        $view = $this->create_view($data);
        
        // Return the rendered view
        return $view->render();
    }
    
    /**
     * Process settings form submission.
     *
     * @since    1.0.0
     */
    private function process_form_submission() {
        // Check if form was submitted
        if (!isset($_POST['your_mvc_plugin_settings_submit'])) {
            return;
        }
        
        // Verify nonce
        if (!isset($_POST['your_mvc_plugin_settings_nonce']) || 
            !wp_verify_nonce($_POST['your_mvc_plugin_settings_nonce'], 'your_mvc_plugin_settings_save')) {
            add_settings_error(
                'your_mvc_plugin_settings',
                'nonce_error',
                I18n::translate('Security check failed.'),
                'error'
            );
            return;
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            add_settings_error(
                'your_mvc_plugin_settings',
                'permission_error',
                I18n::translate('You do not have permission to change settings.'),
                'error'
            );
            return;
        }
        
        // Process and sanitize settings
        $settings = array();
        
        // Text field
        if (isset($_POST['general_text_field'])) {
            $settings['general_text_field'] = sanitize_text_field($_POST['general_text_field']);
        }
        
        // Checkbox
        $settings['general_checkbox'] = isset($_POST['general_checkbox']) ? 1 : 0;
        
        // Select field
        if (isset($_POST['general_select'])) {
            $valid_options = array('option1', 'option2', 'option3');
            if (in_array($_POST['general_select'], $valid_options)) {
                $settings['general_select'] = $_POST['general_select'];
            } else {
                $settings['general_select'] = 'option1'; // Default
                add_settings_error(
                    'your_mvc_plugin_settings',
                    'invalid_select',
                    I18n::translate('Invalid option selected.'),
                    'error'
                );
            }
        }
        
        // Textarea
        if (isset($_POST['general_textarea'])) {
            $settings['general_textarea'] = sanitize_textarea_field($_POST['general_textarea']);
        }
        
        // Save settings
        update_option('your_mvc_plugin_settings', $settings);
        
        // Add success message
        add_settings_error(
            'your_mvc_plugin_settings',
            'settings_updated',
            I18n::translate('Settings saved successfully.'),
            'success'
        );
    }
    
    /**
     * Get settings data for the view.
     *
     * @since    1.0.0
     * @return   array    Settings data.
     */
    private function get_settings_data() {
        // Get current settings
        $settings = get_option('your_mvc_plugin_settings', array());
        
        // Default values
        $defaults = array(
            'general_text_field' => '',
            'general_checkbox' => 0,
            'general_select' => 'option1',
            'general_textarea' => '',
        );
        
        // Merge with defaults
        $settings = wp_parse_args($settings, $defaults);
        
        return array(
            'plugin_name' => I18n::translate('Your MVC Plugin'),
            'settings' => $settings,
            'nonce' => wp_create_nonce('your_mvc_plugin_settings_save'),
        );
    }
    
    /**
     * Create the view instance.
     *
     * @since    1.0.0
     * @param    array    $data    Data for the view.
     * @return   \Your_MVC_Plugin\Views\Admin\Settings_View    The view instance.
     */
    private function create_view($data) {
        // Load the view class if it doesn't use autoloading
        require_once YOUR_PLUGIN_PATH . 'includes/views/admin/class-settings-view.php';
        
        // Create and return the view instance
        return new \Your_MVC_Plugin\Views\Admin\Settings_View($data);
    }
}