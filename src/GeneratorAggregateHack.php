<?php
namespace JLSalinas\RWGen;

trait GeneratorAggregateHack
{
    public function send($value)
    {
        static $generator = null;
        
        if ($generator === null) {
            if ($value === null) {
                return;
            }
            $generator = $this->getGenerator();
        }
        $generator->send($value);
        if ($value === null) {
            $generator = null;
        }
    }
    
    function __destruct()
    {
        $this->send(null);
    }
}
