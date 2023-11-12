<?php
namespace Generators;

abstract class AbstractWriter implements GeneratorAggregate
{
    private ?\Generator $generator = null;
    private bool $alreadyClosed = false;
    
    abstract protected function writerGenerator(): \Generator;
    
    public function getGenerator(): \Generator
    {
        return $this->writerGenerator();
    }

    public function send($value): void
    {
        if ($this->generator === null) {
            if ($this->alreadyClosed === true) {
                throw new \Exception("Generator is already closed. Cannot accept more data.");
            }
            $this->generator = $this->getGenerator();
        }

        $this->generator->send($value);
        
        if ($value === null) {
            $this->generator = null;
            $this->alreadyClosed = true;
        }
    }
    
    public function __destruct()
    {
        if ($this->generator !== null && $this->alreadyClosed === false) {
            $this->send(null);
        }
    }
}
