<?php
namespace JLSalinas\RWGen\Writers;

use JLSalinas\RWGen\Writer;

class Console extends Writer
{
    protected function outputGenerator()
    {
        while (($data = yield) !== null) {
            print_r($data);
        }
    }
}
