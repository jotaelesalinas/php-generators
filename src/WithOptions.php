<?php
namespace Generators;

/**
 * Provides access to a set of configuration options.
 *
 * Classes using this trait have to:
 *  - declare a static $default_options variable
 *  - call $this->preloadDefaults() in order to load defaults
 *  - call $this->options(...) to get/set options
 */
trait WithOptions
{
    protected $options = null;
    
    public static function getDefaults()
    {
        return static::$default_options ?? [];
    }
    
    private function preloadDefaults()
    {
        $this->options = static::getDefaults();
    }
    
    protected function setOptions(array $options)
    {
        if (is_null($this->options)) {
            $this->preloadDefaults(true);
        }
        
        foreach ($options as $k => $v) {
            if (!isset($this->options[$k])) {
                throw new \Exception("Option $k not allowed.");
            }
            $this->options[$k] = $v;
        }
    }
    
    public function getOption($key = null)
    {
        if (is_null($this->options)) {
            $this->preloadDefaults(true);
        }
        
        return $key === null ? $this->options : ($this->options[$key] ?? null);
    }
}
