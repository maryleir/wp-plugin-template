<?php
namespace Your_MVC_Plugin\Views\Frontend;

use Your_MVC_Plugin\Views\Base_View;
use Your_MVC_Plugin\I18n;

/**
 * Frontend Main View
 * 
 * Renders the public-facing shortcode content
 * 
 * @since      1.0.0
 */
class Main_View extends Base_View {
    
    /**
     * The display type.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $display_type    The display type.
     */
    protected $display_type;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $display_type    The display type.
     * @param    array     $data            Data for the view.
     */
    public function __construct($display_type, $data) {
        $this->display_type = $display_type;
        parent::__construct('', $data);
    }
    
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
        echo '<div id="' . esc_attr($this->data['id']) . '" class="your-mvc-plugin-container ';
        echo 'your-mvc-plugin-display-' . esc_attr($this->display_type) . ' ';
        echo esc_attr($this->data['class']) . '">';
        
        // Display title if provided
        if (!empty($this->data['title'])) {
            echo '<h2 class="your-mvc-plugin-title">' . esc_html($this->data['title']) . '</h2>';
        }
        
        // Render content based on display type
        switch ($this->display_type) {
            case 'list':
                $this->render_list();
                break;
                
            case 'form':
                $this->render_form();
                break;
                
            case 'data':
                $this->render_data();
                break;
                
            // Default display
            default:
                $this->render_default();
                break;
        }
        
        echo '</div>'; // Close main container
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Render list display type.
     *
     * @since    1.0.0
     */
    private function render_list() {
        $items = $this->data['items'];
        
        if (empty($items)) {
            echo '<p class="your-mvc-plugin-empty">' . I18n::translate('No items found.') . '</p>';
            return;
        }
        
        echo '<ul class="your-mvc-plugin-list">';
        
        foreach ($items as $item) {
            echo '<li class="your-mvc-plugin-list-item">';
            echo '<h3 class="your-mvc-plugin-item-title">';
            echo '<a href="' . esc_url($item['permalink']) . '">' . esc_html($item['title']) . '</a>';
            echo '</h3>';
            
            echo '<div class="your-mvc-plugin-item-meta">';
            echo '<span class="your-mvc-plugin-item-date">' . esc_html($item['date']) . '</span>';
            echo '</div>';
            
            echo '<div class="your-mvc-plugin-item-excerpt">' . wp_kses_post($item['excerpt']) . '</div>';
            
            echo '<a href="' . esc_url($item['permalink']) . '" class="your-mvc-plugin-item-link">';
            echo I18n::translate('Read More');
            echo '</a>';
            
            echo '</li>';
        }
        
        echo '</ul>';
    }
    
    /**
     * Render form display type.
     *
     * @since    1.0.0
     */
    private function render_form() {
        $form_fields = $this->data['form_fields'];
        $form_id = $this->data['id'] . '-form';
        
        echo '<form id="' . esc_attr($form_id) . '" class="your-mvc-plugin-form" method="post">';
        
        // Render form fields
        foreach ($form_fields as $field) {
            echo '<div class="your-mvc-plugin-form-field">';
            
            // Label
            echo '<label for="' . esc_attr($form_id . '-' . $field['name']) . '">';
            echo esc_html($field['label']);
            
            if (!empty($field['required'])) {
                echo ' <span class="required">*</span>';
            }
            
            echo '</label>';
            
            // Field
            switch ($field['type']) {
                case 'textarea':
                    echo '<textarea id="' . esc_attr($form_id . '-' . $field['name']) . '" ';
                    echo 'name="' . esc_attr($field['name']) . '" ';
                    echo 'placeholder="' . esc_attr($field['placeholder']) . '" ';
                    
                    if (!empty($field['required'])) {
                        echo 'required ';
                    }
                    
                    echo 'rows="5"></textarea>';
                    break;
                    
                default:
                    echo '<input type="' . esc_attr($field['type']) . '" ';
                    echo 'id="' . esc_attr($form_id . '-' . $field['name']) . '" ';
                    echo 'name="' . esc_attr($field['name']) . '" ';
                    echo 'placeholder="' . esc_attr($field['placeholder']) . '" ';
                    
                    if (!empty($field['required'])) {
                        echo 'required ';
                    }
                    
                    echo '>';
                    break;
            }
            
            // Error message container
            echo '<div class="your-mvc-plugin-form-error" data-field="' . esc_attr($field['name']) . '"></div>';
            
            echo '</div>';
        }
        
        // Submit button
        echo '<div class="your-mvc-plugin-form-submit">';
        echo '<button type="submit" class="your-mvc-plugin-button">' . I18n::translate('Submit') . '</button>';
        echo '</div>';
        
        // Response message container
        echo '<div class="your-mvc-plugin-form-response"></div>';
        
        // Hidden fields for AJAX submission
        echo '<input type="hidden" name="action" value="your_mvc_plugin_public_action">';
        echo '<input type="hidden" name="custom_action" value="submit_form">';
        echo '<input type="hidden" name="nonce" value="' . wp_create_nonce('your_mvc_plugin_public_nonce') . '">';
        
        echo '</form>';
        
        // Add inline JavaScript for form handling
        $this->render_form_script($form_id);
    }
    
    /**
     * Render data display type.
     *
     * @since    1.0.0
     */
    private function render_data() {
        $custom_data = $this->data['custom_data'];
        
        echo '<div class="your-mvc-plugin-data">';
        
        // Display site info
        if (!empty($custom_data['site_info'])) {
            echo '<div class="your-mvc-plugin-site-info">';
            echo '<h3>' . I18n::translate('Site Information') . '</h3>';
            
            echo '<ul>';
            echo '<li><strong>' . I18n::translate('Name') . ':</strong> ' . esc_html($custom_data['site_info']['name']) . '</li>';
            echo '<li><strong>' . I18n::translate('Description') . ':</strong> ' . esc_html($custom_data['site_info']['description']) . '</li>';
            echo '<li><strong>' . I18n::translate('URL') . ':</strong> <a href="' . esc_url($custom_data['site_info']['url']) . '">' . esc_html($custom_data['site_info']['url']) . '</a></li>';
            echo '</ul>';
            
            echo '</div>';
        }
        
        // Display option data
        if (!empty($custom_data['option_data'])) {
            echo '<div class="your-mvc-plugin-option-data">';
            echo '<h3>' . I18n::translate('Plugin Settings') . '</h3>';
            
            echo '<ul>';
            foreach ($custom_data['option_data'] as $key => $value) {
                echo '<li><strong>' . esc_html($key) . ':</strong> ';
                
                if (is_array($value)) {
                    echo esc_html(json_encode($value));
                } else {
                    echo esc_html($value);
                }
                
                echo '</li>';
            }
            echo '</ul>';
            
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    /**
     * Render default display type.
     *
     * @since    1.0.0
     */
    private function render_default() {
        echo '<div class="your-mvc-plugin-content">';
        echo wpautop(wp_kses_post($this->data['content']));
        echo '</div>';
    }
    
    /**
     * Render inline JavaScript for form handling.
     *
     * @since    1.0.0
     * @param    string    $form_id    The form ID.
     */
    private function render_form_script($form_id) {
        ?>
        <script type="text/javascript">
        (function($) {
            'use strict';
            
            $(document).ready(function() {
                $('#<?php echo esc_js($form_id); ?>').on('submit', function(e) {
                    e.preventDefault();
                    
                    var $form = $(this);
                    var $response = $form.find('.your-mvc-plugin-form-response');
                    var $submit = $form.find('.your-mvc-plugin-button');
                    
                    // Clear previous errors
                    $form.find('.your-mvc-plugin-form-error').html('');
                    
                    // Disable submit button
                    $submit.prop('disabled', true);
                    
                    // Show loading message
                    $response.html('<p class="loading"><?php echo esc_js(I18n::translate('Submitting...')); ?></p>');
                    
                    // Collect form data
                    var formData = $form.serialize();
                    
                    // Send AJAX request
                    $.ajax({
                        url: your_mvc_plugin_public.ajax_url,
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            // Re-enable submit button
                            $submit.prop('disabled', false);
                            
                            if (response.success) {
                                // Show success message
                                $response.html('<p class="success">' + response.data.message + '</p>');
                                
                                // Reset form
                                $form.trigger('reset');
                            } else {
                                // Show error message
                                $response.html('<p class="error">' + response.data.message + '</p>');
                                
                                // Show field-specific errors
                                if (response.data.errors) {
                                    $.each(response.data.errors, function(field, error) {
                                        $form.find('.your-mvc-plugin-form-error[data-field="' + field + '"]').html(error);
                                    });
                                }
                            }
                        },
                        error: function() {
                            // Re-enable submit button
                            $submit.prop('disabled', false);
                            
                            // Show error message
                            $response.html('<p class="error"><?php echo esc_js(I18n::translate('An error occurred. Please try again later.')); ?></p>');
                        }
                    });
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}