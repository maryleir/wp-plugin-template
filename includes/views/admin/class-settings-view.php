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
class Settings_View extends Base_View {
    
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
            
            <?php settings_errors('your_mvc_plugin_settings'); ?>
            
            <form method="post" action="">
                <?php $this->render_settings_form(); ?>
                
                <input type="hidden" name="your_mvc_plugin_settings_nonce" value="<?php echo esc_attr($this->data['nonce']); ?>" />
                <p class="submit">
                    <input type="submit" name="your_mvc_plugin_settings_submit" class="button button-primary" value="<?php echo I18n::translate('Save Settings'); ?>" />
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
        $current_page = 'your-mvc-plugin-settings';
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
    private function render_settings_form() {
        $settings = $this->data['settings'];
        ?>
        <div class="your-mvc-plugin-settings-container">
            <h2><?php echo I18n::translate('General Settings'); ?></h2>
            
            <table class="form-table" role="presentation">
                <tbody>
                    <!-- Text Field -->
                    <tr>
                        <th scope="row">
                            <label for="general_text_field"><?php echo I18n::translate('Text Field'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="general_text_field" name="general_text_field" 
                                value="<?php echo esc_attr($settings['general_text_field']); ?>" class="regular-text" />
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
                                    value="1" <?php checked(1, $settings['general_checkbox']); ?> />
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
                                <option value="option1" <?php selected('option1', $settings['general_select']); ?>>
                                    <?php echo I18n::translate('Option 1'); ?>
                                </option>
                                <option value="option2" <?php selected('option2', $settings['general_select']); ?>>
                                    <?php echo I18n::translate('Option 2'); ?>
                                </option>
                                <option value="option3" <?php selected('option3', $settings['general_select']); ?>>
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
                                class="large-text"><?php echo esc_textarea($settings['general_textarea']); ?></textarea>
                            <p class="description"><?php echo I18n::translate('Enter multiple lines of text.'); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
}