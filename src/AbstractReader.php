<?php
namespace Generators;

abstract class AbstractReader implements \IteratorAggregate
{
    abstract protected function readerGenerator(): \Generator;
    
    public function getIterator(): \Generator
    {
        return $this->readerGenerator();
    }
}
