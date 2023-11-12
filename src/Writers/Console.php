<?php
namespace Generators\Writers;

use Generators\AbstractWriter;

class Console extends AbstractWriter
{
    protected function writerGenerator(): \Generator
    {
        while (($data = yield) !== null) {
            print_r($data);
            echo PHP_EOL;
        }
    }
}
