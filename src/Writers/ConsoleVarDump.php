<?php
namespace JLSalinas\RWGen\Writers;

use JLSalinas\RWGen\Writer;

class ConsoleVarDump extends Writer
{
    protected function outputGenerator()
    {
        while (($data = yield) !== null) {
            var_dump($data);
        }
    }
}
