<?php
namespace Your_MVC_Plugin\Views\Frontend;

use Your_MVC_Plugin\Views\Base_View;
use Your_MVC_Plugin\I18n;

/**
 * Feature View
 * 
 * Renders the feature shortcode content
 * 
 * @since      1.0.0
 */
class Feature_View extends Base_View {
    
    /**
     * Render the view.
     *
     * @since    1.0.0
     * @return   string    The rendered view.
     */
    public function render() {
        // Start output buffering
        ob_start();
        
        // Main container with ID and classes
        echo '<div id="' . esc_attr($this->data['id']) . '" class="your-mvc-plugin-feature ';
        echo 'your-mvc-plugin-feature-' . esc_attr($this->data['feature']) . ' ';
        echo 'your-mvc-plugin-style-' . esc_attr($this->data['style']) . '">';
        
        // Render content based on feature type
        switch ($this->data['feature']) {
            case 'carousel':
                $this->render_carousel();
                break;
                
            case 'tabs':
                $this->render_tabs();
                break;
                
            case 'stats':
                $this->render_statistics();
                break;
                
            // Default feature
            default:
                $this->render_default_feature();
                break;
        }
        
        echo '</div>'; // Close main container
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Render carousel feature.
     *
     * @since    1.0.0
     */
    private function render_carousel() {
        $items = $this->data['items'];
        $unique_id = $this->data['id'];
        
        if (empty($items)) {
            echo '<p class="your-mvc-plugin-empty">' . I18n::translate('No items found for carousel.') . '</p>';
            return;
        }
        
        // Enqueue slider assets if needed
        wp_enqueue_script('jquery');
        
        echo '<div class="your-mvc-plugin-carousel">';
        
        // Carousel slides
        echo '<div class="your-mvc-plugin-carousel-slides">';
        
        foreach ($items as $index => $item) {
            echo '<div class="your-mvc-plugin-carousel-slide' . ($index === 0 ? ' active' : '') . '" data-slide="' . esc_attr($index) . '">';
            
            // Image
            echo '<div class="your-mvc-plugin-carousel-image">';
            echo '<img src="' . esc_url($item['image_url']) . '" alt="' . esc_attr($item['title']) . '" ';
            echo 'width="' . esc_attr($item['image_width']) . '" height="' . esc_attr($item['image_height']) . '">';
            echo '</div>';
            
            // Content overlay
            echo '<div class="your-mvc-plugin-carousel-content">';
            echo '<h3>' . esc_html($item['title']) . '</h3>';
            echo '<p>' . wp_kses_post($item['excerpt']) . '</p>';
            echo '<a href="' . esc_url($item['permalink']) . '" class="your-mvc-plugin-button">';
            echo I18n::translate('Read More');
            echo '</a>';
            echo '</div>';
            
            echo '</div>'; // Close slide
        }
        
        echo '</div>'; // Close slides
        
        // Navigation controls
        echo '<div class="your-mvc-plugin-carousel-controls">';
        echo '<button class="your-mvc-plugin-carousel-prev" data-target="' . esc_attr($unique_id) . '">';
        echo '&laquo; ' . I18n::translate('Previous');
        echo '</button>';
        
        // Indicators
        echo '<div class="your-mvc-plugin-carousel-indicators">';
        foreach ($items as $index => $item) {
            echo '<button class="your-mvc-plugin-carousel-indicator' . ($index === 0 ? ' active' : '') . '" ';
            echo 'data-target="' . esc_attr($unique_id) . '" data-slide-to="' . esc_attr($index) . '">';
            echo ($index + 1);
            echo '</button>';
        }
        echo '</div>'; // Close indicators
        
        echo '<button class="your-mvc-plugin-carousel-next" data-target="' . esc_attr($unique_id) . '">';
        echo I18n::translate('Next') . ' &raquo;';
        echo '</button>';
        echo '</div>'; // Close controls
        
        echo '</div>'; // Close carousel
        
        // Add inline JavaScript for carousel functionality
        $this->render_carousel_script($unique_id);
    }
    
    /**
     * Render tabs feature.
     *
     * @since    1.0.0
     */
    private function render_tabs() {
        $tabs = $this->data['tabs'];
        $unique_id = $this->data['id'];
        
        if (empty($tabs)) {
            echo '<p class="your-mvc-plugin-empty">' . I18n::translate('No tabs found.') . '</p>';
            return;
        }
        
        echo '<div class="your-mvc-plugin-tabs">';
        
        // Tab navigation
        echo '<ul class="your-mvc-plugin-tabs-nav">';
        foreach ($tabs as $index => $tab) {
            echo '<li class="your-mvc-plugin-tab-nav-item' . ($index === 0 ? ' active' : '') . '">';
            echo '<a href="#' . esc_attr($unique_id . '-' . $tab['id']) . '" data-target="' . esc_attr($tab['id']) . '">';
            echo esc_html($tab['title']);
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
        
        // Tab content
        echo '<div class="your-mvc-plugin-tabs-content">';
        foreach ($tabs as $index => $tab) {
            echo '<div id="' . esc_attr($unique_id . '-' . $tab['id']) . '" ';
            echo 'class="your-mvc-plugin-tab-content' . ($index === 0 ? ' active' : '') . '" ';
            echo 'data-tab="' . esc_attr($tab['id']) . '">';
            echo wpautop(wp_kses_post($tab['content']));
            echo '</div>';
        }
        echo '</div>';
        
        echo '</div>'; // Close tabs
        
        // Add inline JavaScript for tab functionality
        $this->render_tabs_script($unique_id);
    }
    
    /**
     * Render statistics feature.
     *
     * @since    1.0.0
     */
    private function render_statistics() {
        $statistics = $this->data['statistics'];
        
        if (empty($statistics)) {
            echo '<p class="your-mvc-plugin-empty">' . I18n::translate('No statistics found.') . '</p>';
            return;
        }
        
        // Enqueue dashicons
        wp_enqueue_style('dashicons');
        
        echo '<div class="your-mvc-plugin-statistics">';
        
        foreach ($statistics as $stat) {
            echo '<div class="your-mvc-plugin-statistic">';
            
            // Icon
            echo '<div class="your-mvc-plugin-statistic-icon">';
            echo '<span class="dashicons ' . esc_attr($stat['icon']) . '"></span>';
            echo '</div>';
            
            // Value and label
            echo '<div class="your-mvc-plugin-statistic-content">';
            echo '<div class="your-mvc-plugin-statistic-value">' . esc_html(number_format_i18n($stat['value'])) . '</div>';
            echo '<div class="your-mvc-plugin-statistic-label">' . esc_html($stat['label']) . '</div>';
            echo '</div>';
            
            echo '</div>'; // Close statistic
        }
        
        echo '</div>'; // Close statistics
    }
    
    /**
     * Render default feature.
     *
     * @since    1.0.0
     */
    private function render_default_feature() {
        echo '<div class="your-mvc-plugin-default-feature">';
        echo wpautop(wp_kses_post($this->data['content']));
        echo '</div>';
    }
    
    /**
     * Render inline JavaScript for carousel functionality.
     *
     * @since    1.0.0
     * @param    string    $unique_id    The unique ID for the carousel.
     */
    private function render_carousel_script($unique_id) {
        ?>
        <script type="text/javascript">
        (function($) {
            'use strict';
            
            $(document).ready(function() {
                var $carousel = $('#<?php echo esc_js($unique_id); ?>');
                var $slides = $carousel.find('.your-mvc-plugin-carousel-slide');
                var $indicators = $carousel.find('.your-mvc-plugin-carousel-indicator');
                var currentSlide = 0;
                var totalSlides = $slides.length;
                var interval;
                
                // Function to show a specific slide
                function showSlide(index) {
                    // Normalize index
                    if (index >= totalSlides) {
                        index = 0;
                    }
                    
                    if (index < 0) {
                        index = totalSlides - 1;
                    }
                    
                    // Update slides
                    $slides.removeClass('active');
                    $slides.eq(index).addClass('active');
                    
                    // Update indicators
                    $indicators.removeClass('active');
                    $indicators.eq(index).addClass('active');
                    
                    // Update current slide index
                    currentSlide = index;
                }
                
                // Initialize automatic cycling
                function startInterval() {
                    interval = setInterval(function() {
                        showSlide(currentSlide + 1);
                    }, 5000); // Change slide every 5 seconds
                }
                
                // Clear interval
                function stopInterval() {
                    clearInterval(interval);
                }
                
                // Start the carousel
                startInterval();
                
                // Next button
                $carousel.find('.your-mvc-plugin-carousel-next').on('click', function() {
                    stopInterval();
                    showSlide(currentSlide + 1);
                    startInterval();
                });
                
                // Previous button
                $carousel.find('.your-mvc-plugin-carousel-prev').on('click', function() {
                    stopInterval();
                    showSlide(currentSlide - 1);
                    startInterval();
                });
                
                // Indicator buttons
                $indicators.on('click', function() {
                    var index = $(this).data('slide-to');
                    
                    stopInterval();
                    showSlide(index);
                    startInterval();
                });
                
                // Pause on hover
                $carousel.hover(
                    function() { stopInterval(); },
                    function() { startInterval(); }
                );
            });
        })(jQuery);
        </script>
        <?php
    }
    
    /**
     * Render inline JavaScript for tabs functionality.
     *
     * @since    1.0.0
     * @param    string    $unique_id    The unique ID for the tabs.
     */
    private function render_tabs_script($unique_id) {
        ?>
        <script type="text/javascript">
        (function($) {
            'use strict';
            
            $(document).ready(function() {
                var $tabs = $('#<?php echo esc_js($unique_id); ?>');
                
                // Handle tab navigation
                $tabs.find('.your-mvc-plugin-tab-nav-item a').on('click', function(e) {
                    e.preventDefault();
                    
                    var $this = $(this);
                    var target = $this.data('target');
                    
                    // Update tab navigation
                    $tabs.find('.your-mvc-plugin-tab-nav-item').removeClass('active');
                    $this.parent().addClass('active');
                    
                    // Update tab content
                    $tabs.find('.your-mvc-plugin-tab-content').removeClass('active');
                    $tabs.find('.your-mvc-plugin-tab-content[data-tab="' + target + '"]').addClass('active');
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}