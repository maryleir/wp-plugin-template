<?php
namespace Your_MVC_Plugin;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 */
class Plugin {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * Define the core functionality of the plugin.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_api_controllers();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        // The class responsible for orchestrating the actions and filters of the core plugin.
        require_once YOUR_PLUGIN_PATH . 'includes/class-loader.php';
        
        // The class responsible for defining internationalization functionality of the plugin.
        require_once YOUR_PLUGIN_PATH . 'includes/class-i18n.php';
        
        // MVC Components
        require_once YOUR_PLUGIN_PATH . 'includes/models/class-base-model.php';
        require_once YOUR_PLUGIN_PATH . 'includes/views/class-base-view.php';
        require_once YOUR_PLUGIN_PATH . 'includes/controllers/class-base-controller.php';
        
        // The class responsible for defining all actions that occur in the admin area.
        require_once YOUR_PLUGIN_PATH . 'admin/class-admin.php';
        
        // The class responsible for defining all actions that occur in the public-facing side.
        require_once YOUR_PLUGIN_PATH . 'public/class-public.php';
        
        // Load Auth Handler
        require_once YOUR_PLUGIN_PATH . 'includes/auth/class-jwt-auth-handler.php';
    
        // Load API Controller
        require_once YOUR_PLUGIN_PATH . 'includes/controllers/class-api-controller.php';
  
        
        // Create an instance of the loader
        $this->loader = new Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new I18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Admin();
        
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_menu_pages');
    }

    /**
     * Register all of the hooks related to the public-facing functionality.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new \Your_MVC_Plugin\Frontend\Public_Frontend();
        
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }
    
    /**
     * Define API Controllers.
     *
     * @since    1.0.0
     * @access   private
     */
     
    private function define_api_controllers() {
        // Initialize API Controller
        new \Your_MVC_Plugin\Controllers\API_Controller($this->loader);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }
}