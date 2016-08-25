<?php
namespace JLSalinas\RWGen\Writers;

use JLSalinas\RWGen\Writer;

class Console extends Writer
{
    const JSON    = 0;
    const PRINTR  = 1;
    const VARDUMP = 2;
    
    protected function outputGenerator($transform = self::JSON)
    {
        if (!is_callable($transform)) {
            switch ($transform) {
                case self::PRINTR:
                    $transform = function ($item) {
                        print_r($item);
                    };
                    break;
                case self::VARDUMP:
                    $transform = function ($item) {
                        var_dump($item);
                    };
                    break;
                case self::JSON:
                default:
                    $transform = function ($item) {
                        return json_encode($item) . "\n";
                    };
                    break;
            }
        }
        while (($data = yield) !== null) {
            $trans = $transform($data);
            if (is_string($trans)) {
                echo $trans;
            }
        }
    }
}
