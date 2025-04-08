<?php
namespace Your_MVC_Plugin\Controllers;

/**
 * Base Controller Class
 *
 * All plugin controllers should extend this class.
 *
 * @since      1.0.0
 */
abstract class Base_Controller {
    /**
     * The plugin loader.
     *
     * @since    1.0.0
     * @access   protected
     * @var      \Your_MVC_Plugin\Loader    $loader    The plugin loader.
     */
    protected $loader;
    
    /**
     * Constructor.
     *
     * @since    1.0.0
     * @param    \Your_MVC_Plugin\Loader    $loader    The plugin loader.
     */
    public function __construct($loader) {
        $this->loader = $loader;
        $this->register_hooks();
    }
    
    /**
     * Register the controller hooks.
     *
     * All controller specific hooks should be registered here.
     *
     * @since    1.0.0
     */
    abstract protected function register_hooks();
    
    /**
     * Create a new view instance.
     *
     * @since    1.0.0
     * @param    string    $view_name    The view name.
     * @param    array     $data         Optional. The view data.
     * @return   \Your_MVC_Plugin\Views\Base_View    The view instance.
     */
    protected function view($view_name, $data = array()) {
        $view_class = '\\Your_MVC_Plugin\\Views\\' . ucfirst($view_name) . '_View';
        
        if (class_exists($view_class)) {
            return new $view_class($data);
        }
        
        // If the specific view class doesn't exist, create a generic view
        return new \Your_MVC_Plugin\Views\Base_View('', $data);
    }
    
    

    /**
     * Create a new model instance.
     *
     * @since    1.0.0
     * @param    string    $model_name    The model name.
     * @param    array     $data          Optional. The model data.
     * @return   \Your_MVC_Plugin\Models\Base_Model    The model instance.
     */
    protected function model($model_name, $data = array()) {
        $model_class = '\\Your_MVC_Plugin\\Models\\' . ucfirst($model_name) . '_Model';
        
        if (class_exists($model_class)) {
            $model = new $model_class();
            
            // Set the model data if provided
            foreach ($data as $key => $value) {
                $model->set($key, $value);
            }
            
            return $model;
        }
        
        return null;
    }
}