<?php
namespace JLSalinas\RWGen;

/**
 * Provides access to a set of configuration options.
 * 
 * Classes using this trait have to:
 *  - declare a static $default_options variable
 *  - call $this->preloadDefaults() in order to load defaults
 *  - call $this->options(...) to get/set options
 */
trait WithOptions {
    /**
     * The options of the instance.
     * 
     * They are the class-wide default options, overwritten with the instance-specific options.
     * 
     * @var array $options
     */
    protected $options = null;
    
    /**
     * Get the default options of the class where this trait is used,
     * declared as `static $default_options`.
     * 
     * @return array Default options or empty array if none.
     */
    public static function getDefaults () {
        return isset(static::$default_options) ? static::$default_options : array();
    }
    
    /**
     * Reset $options to static::$default_options, if it exists.
     */
    private function preloadDefaults () {
        $this->options = array();
        $defaults = static::getDefaults() ?: array();
        foreach ( $defaults as $k => $v ) {
            $this->options[$k] = $v;
        }
    }
    
    /*
     * Set the options for the current instance.
     * 
     * @param array $options Associative array with option-name => option-value pairs.
     *                       The name of all the options (the keys of the array) must exist in static::$default_options.
     */
    protected function setOptions ($options) {
        if ( is_null($this->options) ) {
            $this->preloadDefaults(true);
        }
        
        foreach ( $options as $k => $v ) {
            if ( !isset($this->options[$k]) ) {
                throw new \Exception("Option $k not allowed.");
            }
            $this->options[$k] = $v;
        }
    }
    
    /*
     * Retrieves the value of an option or all of them.
     * 
     * @param string $key Optional name of the option to retrieve.
     * 
     * @return mixed The value of the option with name $key or the whole $options array if no $key argument is provided.
     */
    public function getOption ($key = null) {
        if ( is_null($this->options) ) {
            $this->preloadDefaults(true);
        }
        
        return $key === null ? $this->options : $this->options[$key];
    }
}
