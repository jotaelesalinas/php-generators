<?php
namespace JLSalinas\RWGen;

/**
 * Base class to be extended by the specific writers.
 *
 * The only method that the derived classes must implement is `outputGenerator()`.
 *
 * Of course, they can implement many other helper methods as needed.
 */
abstract class Writer implements GeneratorAggregate {
    use GeneratorAggregateHack;
    use WithOptions;
    
    /**
     * Generator method that has to be implemented by derived classes.
     * 
     * @param mixed yield Item to be consumed by the generator, via `yield` keyword. `null` to finish.
     */
    abstract protected function outputGenerator ();
    
    /**
     * Returns the generator; in this case the one returned by the abstract method `outputGenerator()`.
     * 
     * @return Generator
     */
    public function getGenerator () {
        return $this->outputGenerator();
    }
}
