<?php
namespace Generators\Writers;

use Generators\AbstractWriter;

class ParallelWriters extends AbstractWriter
{
    private $writers;
    
    public function __construct(AbstractWriter ...$writers)
    {
        $this->writers = $writers;
    }
    
    protected function writerGenerator(): \Generator
    {
        $data = null;
        do {
            $data = yield;
            foreach ($this->writers as $writer) {
                $writer->send($data);
            }
        } while($data !== null);
    }
}
