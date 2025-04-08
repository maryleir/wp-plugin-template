<?php
namespace Your_MVC_Plugin\Views\Admin;

use Your_MVC_Plugin\Views\Base_View;
use Your_MVC_Plugin\I18n;

/**
 * Main Admin View
 * 
 * Renders the main admin page
 * 
 * @since      1.0.0
 */
class Main_View extends Base_View {
    
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
            <h1><?php echo esc_html($this->data['plugin_name']); ?></h1>
            
            <?php $this->render_tabs(); ?>
            
            <div class="your-mvc-plugin-dashboard">
                <?php $this->render_dashboard(); ?>
            </div>
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
        $current_page = 'your-mvc-plugin';
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
     * Render the dashboard content.
     *
     * @since    1.0.0
     */
    private function render_dashboard() {
        $stats = $this->data['stats'];
        $version = $this->data['version'];
        $recent_posts = $this->data['recent_posts'];
        ?>
        <div class="your-mvc-plugin-welcome-panel">
            <div class="your-mvc-plugin-welcome-panel-content">
                <h2><?php echo I18n::sprintf('Welcome to Your MVC Plugin %s', $version); ?></h2>
                <p class="about-description"><?php echo I18n::translate('This is the main dashboard of your plugin.'); ?></p>
                
                <div class="your-mvc-plugin-welcome-panel-column-container">
                    <div class="your-mvc-plugin-welcome-panel-column">
                        <h3><?php echo I18n::translate('Site Statistics'); ?></h3>
                        <ul>
                            <li><?php echo I18n::sprintf('Total Posts: <strong>%d</strong>', $stats['total_posts']); ?></li>
                            <li><?php echo I18n::sprintf('Total Pages: <strong>%d</strong>', $stats['total_pages']); ?></li>
                            <li><?php echo I18n::sprintf('Total Users: <strong>%d</strong>', $stats['total_users']); ?></li>
                            <li><?php echo I18n::sprintf('Current Time: <strong>%s</strong>', date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($stats['current_time']))); ?></li>
                        </ul>
                    </div>
                    
                    <div class="your-mvc-plugin-welcome-panel-column">
                        <h3><?php echo I18n::translate('Recent Posts'); ?></h3>
                        <?php if (!empty($recent_posts)) : ?>
                            <ul>
                                <?php foreach ($recent_posts as $post) : ?>
                                    <li>
                                        <a href="<?php echo get_edit_post_link($post->ID); ?>">
                                            <?php echo esc_html($post->post_title); ?>
                                        </a>
                                        <span class="your-mvc-plugin-post-date">
                                            <?php echo get_the_date('', $post); ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p><?php echo I18n::translate('No recent posts found.'); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="your-mvc-plugin-welcome-panel-column">
                        <h3><?php echo I18n::translate('Quick Links'); ?></h3>
                        <ul>
                            <li><a href="<?php echo admin_url('admin.php?page=your-mvc-plugin-settings'); ?>"><?php echo I18n::translate('Plugin Settings'); ?></a></li>
                            <li><a href="<?php echo admin_url('admin.php?page=your-mvc-plugin-custom'); ?>"><?php echo I18n::translate('Custom Page'); ?></a></li>
                            <li><a href="https://example.com/documentation" target="_blank"><?php echo I18n::translate('Documentation'); ?></a></li>
                            <li><a href="https://example.com/support" target="_blank"><?php echo I18n::translate('Support'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}