<?php
namespace JLSalinas\RWGen;

abstract class Reader implements \IteratorAggregate {
    use WithOptions;
    
    abstract protected function inputGenerator ();
    
    public function getIterator () {
        return $this->inputGenerator();
    }
}
