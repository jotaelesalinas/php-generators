<?php
namespace Generators\Writers;

use Generators\AbstractWriter;

class ConsoleVarDump extends AbstractWriter
{
    protected function writerGenerator(): \Generator
    {
        while (($data = yield) !== null) {
            var_dump($data);
        }
    }
}
