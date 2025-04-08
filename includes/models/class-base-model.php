<?php
namespace Your_MVC_Plugin\Models;

/**
 * Base Model Class
 *
 * All plugin models should extend this class.
 *
 * @since      1.0.0
 */
abstract class Base_Model {
    /**
     * The model data.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $data    The model data.
     */
    protected $data = array();
    
    /**
     * Validation errors.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $errors    Validation errors.
     */
    protected $errors = array();
    
    /**
     * Get a model attribute.
     *
     * @since    1.0.0
     * @param    string    $key       The attribute key.
     * @param    mixed     $default   Optional. Default value if key doesn't exist.
     * @return   mixed                The attribute value.
     */
    public function get($key, $default = null) {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
    
    /**
     * Set a model attribute.
     *
     * @since    1.0.0
     * @param    string    $key      The attribute key.
     * @param    mixed     $value    The attribute value.
     * @return   self                For method chaining.
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }
    
    /**
     * Validate the model data.
     *
     * @since    1.0.0
     * @return   boolean   True if the data is valid, false otherwise.
     */
    abstract public function validate();
    
    /**
     * Save the model.
     *
     * @since    1.0.0
     * @return   boolean|integer   The insert ID on success, false on failure.
     */
    abstract public function save();
    
    /**
     * Get all validation errors.
     *
     * @since    1.0.0
     * @return   array     The validation errors.
     */
    public function get_errors() {
        return $this->errors;
    }
}