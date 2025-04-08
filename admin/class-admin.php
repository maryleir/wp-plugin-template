<?php
namespace Your_MVC_Plugin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for the admin area.
 *
 * @since      1.0.0
 */
class Admin {

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
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            YOUR_PLUGIN_URL . 'admin/css/admin.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            YOUR_PLUGIN_URL . 'admin/js/admin.js',
            array('jquery'),
            $this->version,
            false
        );

        // Localize the script with new data
        wp_localize_script(
            $this->plugin_name,
            'your_mvc_plugin_admin',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('your_mvc_plugin_nonce'),
                'i18n' => array(
                    'save_success' => I18n::translate('Settings saved successfully!'),
                    'save_error' => I18n::translate('Error saving settings. Please try again.'),
                ),
            )
        );
    }

    /**
     * Add menu pages to the admin dashboard.
     *
     * @since    1.0.0
     */
    public function add_menu_pages() {
        // Main menu page
        add_menu_page(
            I18n::translate('Your MVC Plugin'),         // Page title
            I18n::translate('MVC Plugin'),              // Menu title
            'manage_options',                           // Capability
            'your-mvc-plugin',                          // Menu slug
            array($this, 'display_main_page'),          // Callback function
            'dashicons-admin-plugins',                  // Icon URL
            100                                         // Position
        );

        // Settings submenu page
        add_submenu_page(
            'your-mvc-plugin',                          // Parent slug
            I18n::translate('Settings'),                // Page title
            I18n::translate('Settings'),                // Menu title
            'manage_options',                           // Capability
            'your-mvc-plugin-settings',                 // Menu slug
            array($this, 'display_settings_page')       // Callback function
        );

        // Custom submenu page
        add_submenu_page(
            'your-mvc-plugin',                          // Parent slug
            I18n::translate('Custom Page'),             // Page title
            I18n::translate('Custom Page'),             // Menu title
            'manage_options',                           // Capability
            'your-mvc-plugin-custom',                   // Menu slug
            array($this, 'display_custom_page')         // Callback function
        );
    }

    /**
     * Display the main plugin admin page.
     *
     * @since    1.0.0
     */
    public function display_main_page() {
    
        require_once YOUR_PLUGIN_PATH . 'includes/controllers/admin/class-main-controller.php';

        // Instantiate the view controller
        $controller = new Controllers\Admin\Main_Controller($this);
                
        // Display the view
        echo $controller->display();
    }

    /**
     * Display the settings page.
     *
     * @since    1.0.0
     */
    public function display_settings_page() {
        require_once YOUR_PLUGIN_PATH . 'includes/controllers/admin/class-settings-controller.php';

        // Instantiate the view controller
        $controller = new Controllers\Admin\Settings_Controller($this);
        
        // Display the view
        echo $controller->display();
    }

    /**
     * Display the custom page.
     *
     * @since    1.0.0
     */
    public function display_custom_page() {
    
        require_once YOUR_PLUGIN_PATH . 'includes/controllers/admin/class-custom-controller.php';
        // Instantiate the view controller
        $controller = new Controllers\Admin\Custom_Controller($this);
        
        // Display the view
        echo $controller->display();
    }

    /**
     * Register plugin settings.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        register_setting(
            'your_mvc_plugin_settings',              // Option group
            'your_mvc_plugin_settings',              // Option name
            array($this, 'validate_settings')        // Sanitize callback
        );

        add_settings_section(
            'your_mvc_plugin_general_section',       // ID
            I18n::translate('General Settings'),     // Title
            array($this, 'render_settings_section'), // Callback
            'your_mvc_plugin_settings_page'          // Page
        );

        add_settings_field(
            'setting_field_1',                       // ID
            I18n::translate('Setting One'),          // Title
            array($this, 'render_setting_field_1'),  // Callback
            'your_mvc_plugin_settings_page',         // Page
            'your_mvc_plugin_general_section'        // Section
        );

        add_settings_field(
            'setting_field_2',                       // ID
            I18n::translate('Setting Two'),          // Title
            array($this, 'render_setting_field_2'),  // Callback
            'your_mvc_plugin_settings_page',         // Page
            'your_mvc_plugin_general_section'        // Section
        );
    }

    /**
     * Render the settings section description.
     *
     * @since    1.0.0
     */
    public function render_settings_section() {
        echo '<p>' . I18n::translate('Configure the general settings for the plugin.') . '</p>';
    }

    /**
     * Render the first setting field.
     *
     * @since    1.0.0
     */
    public function render_setting_field_1() {
        $options = get_option('your_mvc_plugin_settings');
        $value = isset($options['setting_field_1']) ? $options['setting_field_1'] : '';
        
        echo '<input type="text" id="setting_field_1" name="your_mvc_plugin_settings[setting_field_1]" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . I18n::translate('Description for setting one.') . '</p>';
    }

    /**
     * Render the second setting field.
     *
     * @since    1.0.0
     */
    public function render_setting_field_2() {
        $options = get_option('your_mvc_plugin_settings');
        $value = isset($options['setting_field_2']) ? $options['setting_field_2'] : '';
        
        echo '<select id="setting_field_2" name="your_mvc_plugin_settings[setting_field_2]">';
        echo '<option value="option1" ' . selected($value, 'option1', false) . '>' . I18n::translate('Option 1') . '</option>';
        echo '<option value="option2" ' . selected($value, 'option2', false) . '>' . I18n::translate('Option 2') . '</option>';
        echo '<option value="option3" ' . selected($value, 'option3', false) . '>' . I18n::translate('Option 3') . '</option>';
        echo '</select>';
        echo '<p class="description">' . I18n::translate('Description for setting two.') . '</p>';
    }

    /**
     * Validate settings fields.
     *
     * @since    1.0.0
     * @param    array    $input    Array of input values.
     * @return   array              Sanitized input values.
     */
    public function validate_settings($input) {
        $validated = array();
        
        // Validate setting field 1
        if (isset($input['setting_field_1'])) {
            $validated['setting_field_1'] = sanitize_text_field($input['setting_field_1']);
        }
        
        // Validate setting field 2
        if (isset($input['setting_field_2'])) {
            $valid_options = array('option1', 'option2', 'option3');
            if (in_array($input['setting_field_2'], $valid_options)) {
                $validated['setting_field_2'] = $input['setting_field_2'];
            } else {
                $validated['setting_field_2'] = 'option1'; // Default value
                add_settings_error(
                    'your_mvc_plugin_settings',
                    'invalid_setting_field_2',
                    I18n::translate('Invalid option selected for Setting Two.')
                );
            }
        }
        
        return $validated;
    }

    /**
     * Register custom AJAX handlers.
     *
     * @since    1.0.0
     */
    public function register_ajax_handlers() {
        add_action('wp_ajax_your_mvc_plugin_ajax_action', array($this, 'handle_ajax_request'));
    }

    /**
     * Handle AJAX requests.
     *
     * @since    1.0.0
     */
    public function handle_ajax_request() {
        // Check security nonce
        if (!check_ajax_referer('your_mvc_plugin_nonce', 'nonce', false)) {
            wp_send_json_error(array('message' => I18n::translate('Security check failed.')));
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => I18n::translate('You do not have permission to perform this action.')));
        }
        
        // Process the AJAX request
        $action = isset($_POST['custom_action']) ? sanitize_text_field($_POST['custom_action']) : '';
        
        switch ($action) {
            case 'save_settings':
                // Process settings save action
                $settings = isset($_POST['settings']) ? $_POST['settings'] : array();
                
                // Validate and sanitize settings
                $sanitized_settings = array();
                foreach ($settings as $key => $value) {
                    $sanitized_settings[$key] = sanitize_text_field($value);
                }
                
                // Save settings
                update_option('your_mvc_plugin_custom_settings', $sanitized_settings);
                
                wp_send_json_success(array(
                    'message' => I18n::translate('Settings saved successfully!'),
                    'settings' => $sanitized_settings
                ));
                break;
                
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
                
            default:
                wp_send_json_error(array('message' => I18n::translate('Invalid action.')));
                break;
        }
    }

    /**
     * Add custom meta boxes.
     *
     * @since    1.0.0
     */
    public function add_meta_boxes() {
        add_meta_box(
            'your_mvc_plugin_meta_box',              // ID
            I18n::translate('MVC Plugin Settings'),  // Title
            array($this, 'render_meta_box'),         // Callback
            'post',                                  // Screen (post type)
            'side',                                  // Context
            'default'                                // Priority
        );
    }

    /**
     * Render custom meta box.
     *
     * @since    1.0.0
     * @param    WP_Post    $post    The post object.
     */
    public function render_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('your_mvc_plugin_meta_box', 'your_mvc_plugin_meta_box_nonce');
        
        // Get existing value
        $value = get_post_meta($post->ID, '_your_mvc_plugin_meta_value', true);
        
        // Render field
        echo '<label for="your_mvc_plugin_meta_field">' . I18n::translate('Custom Field') . '</label>';
        echo '<input type="text" id="your_mvc_plugin_meta_field" name="your_mvc_plugin_meta_field" value="' . esc_attr($value) . '" class="widefat" />';
        echo '<p class="description">' . I18n::translate('Enter a custom value for this post.') . '</p>';
    }

    /**
     * Save meta box data.
     *
     * @since    1.0.0
     * @param    int    $post_id    The post ID.
     */
    public function save_meta_box_data($post_id) {
        // Check if nonce is set
        if (!isset($_POST['your_mvc_plugin_meta_box_nonce'])) {
            return;
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['your_mvc_plugin_meta_box_nonce'], 'your_mvc_plugin_meta_box')) {
            return;
        }
        
        // If this is an autosave, don't do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check if field is set
        if (!isset($_POST['your_mvc_plugin_meta_field'])) {
            return;
        }
        
        // Sanitize and save data
        $meta_value = sanitize_text_field($_POST['your_mvc_plugin_meta_field']);
        update_post_meta($post_id, '_your_mvc_plugin_meta_value', $meta_value);
    }

    /**
     * Add custom action links to plugins page.
     *
     * @since    1.0.0
     * @param    array    $links    Plugin action links.
     * @return   array              Modified action links.
     */
    public function add_action_links($links) {
        $plugin_links = array(
            '<a href="' . admin_url('admin.php?page=your-mvc-plugin-settings') . '">' . I18n::translate('Settings') . '</a>'
        );
        
        return array_merge($plugin_links, $links);
    }
}