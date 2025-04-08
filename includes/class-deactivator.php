<?php
namespace Your_MVC_Plugin;

/**
 * Fired during plugin deactivation.
 *
 * @since      1.0.0
 */
class Deactivator {

    /**
     * Plugin deactivation tasks.
     *
     * This method contains all code that should be run when the plugin is deactivated.
     * This includes cleaning up temporary data, removing options (if desired), etc.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Clean up temporary data
        self::clean_temporary_data();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Clean up any temporary data or caches
     *
     * @since    1.0.0
     */
    private static function clean_temporary_data() {
        // Example of deleting transients
        // delete_transient('your_mvc_plugin_transient');
        
        // Example of removing scheduled events
        // wp_clear_scheduled_hook('your_mvc_plugin_scheduled_event');
    }
}