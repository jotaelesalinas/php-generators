<?php
namespace Generators\Writers;

use Generators\AbstractWriter;
use Generators\WithOptions;

class ConsoleJson extends AbstractWriter
{
    use WithOptions;

    public static $default_options = [
        'pretty' => true,
    ];
    
    protected function writerGenerator(): \Generator
    {
        $flags = $this->getOption('pretty') ? JSON_PRETTY_PRINT : 0;
        while (($data = yield) !== null) {
            echo json_encode($data, $flags) . PHP_EOL;
        }
    }
}
