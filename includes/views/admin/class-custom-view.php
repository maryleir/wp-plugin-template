<?php
namespace Your_MVC_Plugin\Views\Admin;

use Your_MVC_Plugin\Views\Base_View;
use Your_MVC_Plugin\I18n;

/**
 * Settings Admin View
 * 
 * Renders the settings admin page
 * 
 * @since      1.0.0
 */
class Custom_View extends Base_View {
    
    /**
     * Render the view.
     *
     * @since    1.0.0
     * @return   string    The rendered view.
     */
    public function render($template = '') {
        ob_start();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html($this->data['plugin_name']); ?> - <?php echo I18n::translate('Settings'); ?></h1>
            
            <?php $this->render_tabs(); ?>
            
            <?php settings_errors('your_mvc_plugin_custom'); ?>
            
            <form method="post" action="">
                <?php $this->render_custom_form(); ?>
                
                <input type="hidden" name="your_mvc_plugin_custom_nonce" value="<?php echo esc_attr($this->data['nonce']); ?>" />
                <p class="submit">
                    <input type="submit" name="your_mvc_plugin_custom_submit" class="button button-primary" value="<?php echo I18n::translate('Save Custom'); ?>" />
                </p>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render the admin tabs.
     *
     * @since    1.0.0
     */
    private function render_tabs() {
        $current_page = 'your-mvc-plugin-custom';
        ?>
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url('admin.php?page=your-mvc-plugin'); ?>" class="nav-tab <?php echo $current_page === 'your-mvc-plugin' ? 'nav-tab-active' : ''; ?>">
                <?php echo I18n::translate('Dashboard'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=your-mvc-plugin-settings'); ?>" class="nav-tab <?php echo $current_page === 'your-mvc-plugin-settings' ? 'nav-tab-active' : ''; ?>">
                <?php echo I18n::translate('Settings'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=your-mvc-plugin-custom'); ?>" class="nav-tab <?php echo $current_page === 'your-mvc-plugin-custom' ? 'nav-tab-active' : ''; ?>">
                <?php echo I18n::translate('Custom Page'); ?>
            </a>
        </h2>
        <?php
    }
    
    /**
     * Render the settings form.
     *
     * @since    1.0.0
     */
    private function render_custom_form() {
        $custom = $this->data['custom'];
        ?>
        <div class="your-mvc-plugin-custom-container">
            <h2><?php echo I18n::translate('Custom Items'); ?></h2>
            
            <table class="form-table" role="presentation">
                <tbody>
                    <!-- Text Field -->
                    <tr>
                        <th scope="row">
                            <label for="general_text_field"><?php echo I18n::translate('Text Field'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="general_text_field" name="general_text_field" 
                                value="<?php echo esc_attr($custom['general_text_field']); ?>" class="regular-text" />
                            <p class="description"><?php echo I18n::translate('This is a sample text field.'); ?></p>
                        </td>
                    </tr>
                    
                    <!-- Checkbox -->
                    <tr>
                        <th scope="row">
                            <label for="general_checkbox"><?php echo I18n::translate('Checkbox'); ?></label>
                        </th>
                        <td>
                            <label for="general_checkbox">
                                <input type="checkbox" id="general_checkbox" name="general_checkbox" 
                                    value="1" <?php checked(1, $custom['general_checkbox']); ?> />
                                <?php echo I18n::translate('Enable this feature'); ?>
                            </label>
                            <p class="description"><?php echo I18n::translate('This is a sample checkbox.'); ?></p>
                        </td>
                    </tr>
                    
                    <!-- Select Field -->
                    <tr>
                        <th scope="row">
                            <label for="general_select"><?php echo I18n::translate('Select Option'); ?></label>
                        </th>
                        <td>
                            <select id="general_select" name="general_select">
                                <option value="option1" <?php selected('option1', $custom['general_select']); ?>>
                                    <?php echo I18n::translate('Option 1'); ?>
                                </option>
                                <option value="option2" <?php selected('option2', $custom['general_select']); ?>>
                                    <?php echo I18n::translate('Option 2'); ?>
                                </option>
                                <option value="option3" <?php selected('option3', $custom['general_select']); ?>>
                                    <?php echo I18n::translate('Option 3'); ?>
                                </option>
                            </select>
                            <p class="description"><?php echo I18n::translate('Choose from available options.'); ?></p>
                        </td>
                    </tr>
                    
                    <!-- Textarea -->
                    <tr>
                        <th scope="row">
                            <label for="general_textarea"><?php echo I18n::translate('Textarea'); ?></label>
                        </th>
                        <td>
                            <textarea id="general_textarea" name="general_textarea" rows="5" 
                                class="large-text"><?php echo esc_textarea($custom['general_textarea']); ?></textarea>
                            <p class="description"><?php echo I18n::translate('Enter multiple lines of text.'); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
}