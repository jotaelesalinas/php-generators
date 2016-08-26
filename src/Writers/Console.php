<?php
namespace JLSalinas\RWGen\Writers;

use JLSalinas\RWGen\Writer;

class Console extends Writer
{
    const JSON    = 0;
    const PRINTR  = 1;
    const VARDUMP = 2;
    
    protected $transform = null;
    
    public function __construct($transform = self::JSON)
    {
        if (!is_callable($transform)) {
            switch ($transform) {
                case self::PRINTR:
                    $this->transform = function ($item) {
                        print_r($item);
                    };
                    break;
                case self::VARDUMP:
                    $this->transform = function ($item) {
                        var_dump($item);
                    };
                    break;
                case self::JSON:
                default:
                    $this->transform = function ($item) {
                        return json_encode($item) . "\n";
                    };
                    break;
            }
        } else {
            $this->transform = $transform;
        }
    }
    
    protected function outputGenerator()
    {
        $transform = $this->transform;
        while (($data = yield) !== null) {
            $transormed = $transform($data);
            if (is_string($transormed)) {
                echo $transormed;
            }
        }
    }
}
