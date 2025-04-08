<?php
namespace Your_MVC_Plugin;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 */
class I18n {

    /**
     * The domain specified for this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $domain    The domain identifier for this plugin.
     */
    private $domain;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->domain = 'your-mvc-plugin';
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            $this->domain,
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

    /**
     * Get the plugin text domain.
     *
     * @since    1.0.0
     * @return   string    The text domain.
     */
    public function get_domain() {
        return $this->domain;
    }

    /**
     * Translate a string within the plugin domain.
     *
     * Shorthand function for easier translation within the plugin.
     *
     * @since    1.0.0
     * @param    string    $text      The text to translate.
     * @param    string    $context   Optional. Context information for the translators.
     * @return   string               The translated text.
     */
    public static function translate($text, $context = '') {
        if (!empty($context)) {
            return _x($text, $context, 'your-mvc-plugin');
        }

        return __($text, 'your-mvc-plugin');
    }

    /**
     * Translate and echo a string within the plugin domain.
     *
     * Shorthand function for easier translation and output within the plugin.
     *
     * @since    1.0.0
     * @param    string    $text      The text to translate.
     * @param    string    $context   Optional. Context information for the translators.
     */
    public static function _e($text, $context = '') {
        if (!empty($context)) {
            _ex($text, $context, 'your-mvc-plugin');
            return;
        }

        _e($text, 'your-mvc-plugin');
    }

    /**
     * Translate and format a string within the plugin domain.
     *
     * Shorthand function for easier sprintf translation within the plugin.
     *
     * @since    1.0.0
     * @param    string    $text      The text to translate.
     * @param    mixed     $args      Format arguments.
     * @return   string               The formatted translated text.
     */
    public static function sprintf($text, ...$args) {
        return sprintf(self::translate($text), ...$args);
    }

    /**
     * Translate, format and echo a string within the plugin domain.
     *
     * Shorthand function for easier sprintf translation and output within the plugin.
     *
     * @since    1.0.0
     * @param    string    $text      The text to translate.
     * @param    mixed     $args      Format arguments.
     */
    public static function printf($text, ...$args) {
        printf(self::translate($text), ...$args);
    }
}