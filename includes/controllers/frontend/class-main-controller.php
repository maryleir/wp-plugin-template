<?php
namespace Your_MVC_Plugin\Controllers\Frontend;

use Your_MVC_Plugin\Controllers\Base_Controller;
use Your_MVC_Plugin\I18n;

/**
 * Frontend Main Controller
 * 
 * Handles the main public-facing shortcode and functionality
 * 
 * @since      1.0.0
 */
class Main_Controller extends Base_Controller {
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Pass null for loader as we're not registering hooks directly from this controller
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
     * Render the main shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            Shortcode output.
     */
    public function render_shortcode($atts) {
        // Get data for the view
        $data = $this->get_shortcode_data($atts);
        
        // Create the view
        $view = $this->create_view($atts['display'], $data);
        
        // Render and return the view
        return $view->render();
    }
    
    /**
     * Get data for the shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   array             Data for the view.
     */
    private function get_shortcode_data($atts) {
        // Base data
        $data = array(
            'id' => !empty($atts['id']) ? $atts['id'] : 'your-mvc-plugin-' . uniqid(),
            'class' => !empty($atts['class']) ? $atts['class'] : '',
            'title' => $atts['title'],
            'display' => $atts['display'],
        );
        
        // Get additional data based on display type
        switch ($atts['display']) {
            case 'list':
                $data['items'] = $this->get_list_items();
                break;
                
            case 'form':
                $data['form_fields'] = $this->get_form_fields();
                break;
                
            case 'data':
                $data['custom_data'] = $this->get_custom_data();
                break;
                
            // Default display
            default:
                $data['content'] = $this->get_default_content();
                break;
        }
        
        return $data;
    }
    
    /**
     * Get list items for the list display type.
     *
     * @since    1.0.0
     * @return   array    List items.
     */
    private function get_list_items() {
        // Example: Get posts
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 5,
        );
        
        $query = new \WP_Query($args);
        $posts = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $posts[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'permalink' => get_permalink(),
                    'date' => get_the_date(),
                );
            }
            
            wp_reset_postdata();
        }
        
        return $posts;
    }
    
    /**
     * Get form fields for the form display type.
     *
     * @since    1.0.0
     * @return   array    Form fields.
     */
    private function get_form_fields() {
        return array(
            array(
                'type' => 'text',
                'name' => 'name',
                'label' => I18n::translate('Name'),
                'placeholder' => I18n::translate('Enter your name'),
                'required' => true,
            ),
            array(
                'type' => 'email',
                'name' => 'email',
                'label' => I18n::translate('Email'),
                'placeholder' => I18n::translate('Enter your email'),
                'required' => true,
            ),
            array(
                'type' => 'textarea',
                'name' => 'message',
                'label' => I18n::translate('Message'),
                'placeholder' => I18n::translate('Enter your message'),
                'required' => true,
            ),
        );
    }
    
    /**
     * Get custom data for the data display type.
     *
     * @since    1.0.0
     * @return   array    Custom data.
     */
    private function get_custom_data() {
        // Example: Get some option data
        $options = get_option('your_mvc_plugin_settings', array());
        
        return array(
            'option_data' => $options,
            'site_info' => array(
                'name' => get_bloginfo('name'),
                'description' => get_bloginfo('description'),
                'url' => get_bloginfo('url'),
            ),
        );
    }
    
    /**
     * Get default content for the default display type.
     *
     * @since    1.0.0
     * @return   string    Default content.
     */
    private function get_default_content() {
        return I18n::translate('This is the default content for the shortcode. You can customize this in your plugin settings.');
    }
    
    /**
     * Create the view instance.
     *
     * @since    1.0.0
     * @param    string    $display_type    The display type.
     * @param    array     $data            Data for the view.
     * @return   \Your_MVC_Plugin\Views\Frontend\Main_View    The view instance.
     */
    private function create_view($display_type, $data) {
        // Load the view class if it doesn't use autoloading
        require_once YOUR_PLUGIN_PATH . 'includes/views/frontend/class-main-view.php';
        
        // Create and return the view instance
        return new \Your_MVC_Plugin\Views\Frontend\Main_View($display_type, $data);
    }
}