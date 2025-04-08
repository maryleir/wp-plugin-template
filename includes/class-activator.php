<?php
namespace Your_MVC_Plugin;

/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 */
class Activator {

    /**
     * Plugin activation tasks.
     *
     * This method contains all code that should be run when the plugin is activated.
     * This includes creating custom database tables, setting default options, etc.
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Create database tables if needed
        self::create_tables();
        
        // Set default options
        self::set_default_options();
        
        // Add custom capabilities if needed
        self::add_capabilities();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Create any necessary database tables
     *
     * @since    1.0.0
     */
    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        // Example table creation
        // $table_name = $wpdb->prefix . 'your_plugin_table';
        // 
        // $sql = "CREATE TABLE $table_name (
        //     id mediumint(9) NOT NULL AUTO_INCREMENT,
        //     time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        //     name tinytext NOT NULL,
        //     text text NOT NULL,
        //     PRIMARY KEY  (id)
        // ) $charset_collate;";
        // 
        // require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        // dbDelta($sql);
    }
    
    /**
     * Set default plugin options
     *
     * @since    1.0.0
     */
    private static function set_default_options() {
        $options = array(
            'version' => YOUR_PLUGIN_VERSION,
            'setting_1' => 'default_value',
            'setting_2' => true
        );
        
        foreach ($options as $option => $value) {
            if (get_option('your_mvc_plugin_' . $option) === false) {
                add_option('your_mvc_plugin_' . $option, $value);
            }
        }
    }
    
    /**
     * Add custom capabilities to roles if needed
     *
     * @since    1.0.0
     */
    private static function add_capabilities() {
        // Example capability addition
        // $role = get_role('administrator');
        // if ($role) {
        //     $role->add_cap('your_plugin_capability');
        // }
    }
}