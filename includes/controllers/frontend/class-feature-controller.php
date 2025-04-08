<?php
namespace Your_MVC_Plugin\Controllers\Frontend;

use Your_MVC_Plugin\Controllers\Base_Controller;
use Your_MVC_Plugin\I18n;

/**
 * Feature Controller
 * 
 * Handles the feature shortcode and functionality
 * 
 * @since      1.0.0
 */
class Feature_Controller extends Base_Controller {
    
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
     * Render the feature shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            Shortcode output.
     */
    public function render_shortcode($atts) {
        // Get data for the view
        $data = $this->get_feature_data($atts);
        
        // Create the view
        $view = $this->create_view($data);
        
        // Render and return the view
        return $view->render();
    }
    
    /**
     * Get data for the feature shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   array             Data for the view.
     */
    private function get_feature_data($atts) {
        // Base data
        $data = array(
            'id' => !empty($atts['id']) ? $atts['id'] : 'your-mvc-plugin-feature-' . uniqid(),
            'feature' => $atts['feature'],
            'style' => $atts['style'],
        );
        
        // Get feature-specific data
        switch ($atts['feature']) {
            case 'carousel':
                $data['items'] = $this->get_carousel_items();
                break;
                
            case 'tabs':
                $data['tabs'] = $this->get_tabs_data();
                break;
                
            case 'stats':
                $data['statistics'] = $this->get_statistics_data();
                break;
                
            // Default feature
            default:
                $data['content'] = $this->get_default_feature_content();
                break;
        }
        
        return $data;
    }
    
    /**
     * Get carousel items for the carousel feature.
     *
     * @since    1.0.0
     * @return   array    Carousel items.
     */
    private function get_carousel_items() {
        // Example: Get featured images from recent posts
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'meta_query' => array(
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS',
                ),
            ),
        );
        
        $query = new \WP_Query($args);
        $items = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $thumb_id = get_post_thumbnail_id();
                $thumb_url = wp_get_attachment_image_src($thumb_id, 'large');
                
                $items[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'permalink' => get_permalink(),
                    'image_url' => $thumb_url[0],
                    'image_width' => $thumb_url[1],
                    'image_height' => $thumb_url[2],
                );
            }
            
            wp_reset_postdata();
        }
        
        return $items;
    }
    
    /**
     * Get tabs data for the tabs feature.
     *
     * @since    1.0.0
     * @return   array    Tabs data.
     */
    private function get_tabs_data() {
        return array(
            array(
                'id' => 'tab1',
                'title' => I18n::translate('Tab 1'),
                'content' => I18n::translate('This is the content for Tab 1. You can customize this in your plugin settings.'),
            ),
            array(
                'id' => 'tab2',
                'title' => I18n::translate('Tab 2'),
                'content' => I18n::translate('This is the content for Tab 2. You can customize this in your plugin settings.'),
            ),
            array(
                'id' => 'tab3',
                'title' => I18n::translate('Tab 3'),
                'content' => I18n::translate('This is the content for Tab 3. You can customize this in your plugin settings.'),
            ),
        );
    }
    
    /**
     * Get statistics data for the stats feature.
     *
     * @since    1.0.0
     * @return   array    Statistics data.
     */
    private function get_statistics_data() {
        // Example: Get some site statistics
        $users_count = count_users();
        $posts_count = wp_count_posts();
        $comments_count = wp_count_comments();
        
        return array(
            array(
                'label' => I18n::translate('Users'),
                'value' => $users_count['total_users'],
                'icon' => 'dashicons-admin-users',
            ),
            array(
                'label' => I18n::translate('Posts'),
                'value' => $posts_count->publish,
                'icon' => 'dashicons-admin-post',
            ),
            array(
                'label' => I18n::translate('Comments'),
                'value' => $comments_count->approved,
                'icon' => 'dashicons-admin-comments',
            ),
            array(
                'label' => I18n::translate('Pages'),
                'value' => wp_count_posts('page')->publish,
                'icon' => 'dashicons-admin-page',
            ),
        );
    }
    
    /**
     * Get default content for the default feature.
     *
     * @since    1.0.0
     * @return   string    Default content.
     */
    private function get_default_feature_content() {
        return I18n::translate('This is the default content for the feature shortcode. You can customize this in your plugin settings.');
    }
    
    /**
     * Create the view instance.
     *
     * @since    1.0.0
     * @param    array     $data    Data for the view.
     * @return   \Your_MVC_Plugin\Views\Frontend\Feature_View    The view instance.
     */
    private function create_view($data) {
        // Load the view class if it doesn't use autoloading
        require_once YOUR_PLUGIN_PATH . 'includes/views/frontend/class-feature-view.php';
        
        // Create and return the view instance
        return new \Your_MVC_Plugin\Views\Frontend\Feature_View($data);
    }
}