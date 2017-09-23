<?php
namespace JLSalinas\RWGen;

trait GeneratorAggregateHack
{
    protected $generator = null;
    public function send($value)
    {
        if ($this->generator === null) {
            if ($value === null) {
                return;
            }
            $this->generator = $this->getGenerator();
        }
        $this->generator->send($value);
        if ($value === null) {
            $this->generator = null;
        }
    }
    
    public function __destruct()
    {
        $this->send(null);
    }
}
