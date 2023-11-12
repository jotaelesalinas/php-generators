<?php
namespace Generators\Readers;

use Generators\AbstractReader;

class SequentialReaders extends AbstractReader
{
    private $readers;
    
    public function __construct(iterable ...$readers)
    {
        $this->readers = $readers;
    }
    
    protected function readerGenerator(): \Generator
    {
        foreach ($this->readers as $reader) {
            yield from $reader;
        }
    }
}
