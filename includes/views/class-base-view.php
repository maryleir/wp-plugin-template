<?php
namespace Your_MVC_Plugin\Views;

/**
 * Base View Class
 *
 * All plugin views should extend this class.
 *
 * @since      1.0.0
 */
abstract class Base_View {
    /**
     * The view data.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $data    The view data.
     */
    protected $data = array();
    
    /**
     * The view template file.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $template    The view template file.
     */
    protected $template;
    
    /**
     * Constructor.
     *
     * @since    1.0.0
     * @param    string    $template    Optional. The template file path.
     * @param    array     $data        Optional. The view data.
     */
    public function __construct($template = '', $data = array()) {
        $this->template = $template;
        $this->data = $data;
    }
    
    /**
     * Assign a variable to the view.
     *
     * @since    1.0.0
     * @param    string    $key      The variable name.
     * @param    mixed     $value    The variable value.
     * @return   self                For method chaining.
     */
    public function assign($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }
    
    /**
     * Render the view template.
     *
     * @since    1.0.0
     * @param    string    $template    Optional. The template file path.
     * @return   string                 The rendered template.
     */
    public function render($template = '') {
        if (!empty($template)) {
            $this->template = $template;
        }
        
        if (empty($this->template) || !file_exists($this->template)) {
            return '';
        }
        
        // Extract the view data to make variables accessible in the template
        extract($this->data);
        
        // Start output buffering
        ob_start();
        
        // Include the template file
        include $this->template;
        
        // Return the buffered content
        return ob_get_clean();
    }
}