<?php
/**
 * Plugin Name: Your MVC Plugin
 * Plugin URI: https://yourwebsite.com/plugin
 * Description: A WordPress plugin using MVC architecture
 * Version: 1.0.0
 * Author: Mary Leir
 * Author URI: https://yourwebsite.com
 * Text Domain: your-mvc-plugin
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('YOUR_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('YOUR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('YOUR_PLUGIN_VERSION', '1.0.0');

// Include the main plugin class
require_once YOUR_PLUGIN_PATH . 'includes/class-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
function run_your_mvc_plugin() {
    // Instance of the main plugin class
    $plugin = new Your_MVC_Plugin\Plugin();
    $plugin->run();
}

// Hook for plugin activation
register_activation_hook(__FILE__, function() {
    require_once YOUR_PLUGIN_PATH . 'includes/class-activator.php';
    Your_MVC_Plugin\Activator::activate();
});

// Hook for plugin deactivation
register_deactivation_hook(__FILE__, function() {
    require_once YOUR_PLUGIN_PATH . 'includes/class-deactivator.php';
    Your_MVC_Plugin\Deactivator::deactivate();
});

// Run the plugin
run_your_mvc_plugin();