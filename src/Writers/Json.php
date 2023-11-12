<?php
namespace Generators\Writers;

use Generators\AbstractWriter;
use Generators\WithOptions;

class Json extends AbstractWriter
{
    use WithOptions;

    public static $default_options = [
        'pretty' => true,
        'overwrite' => false,
    ];
    
    private $outputfile = null;
    
    public function __construct($outputfile, $options = array())
    {
        $this->outputfile = $outputfile;
        $this->setOptions($options);
        
        if (!$this->getOption('overwrite') && file_exists($outputfile)) {
            throw new \Exception('Output file already exists: ' . $this->outputfile);
        }
    }

    protected function writerGenerator(): \Generator
    {
        // prepare
        $buffer = [];
        
        // repeat
        while (($data = yield) !== null) {
            $buffer[] = $data;
        }
        
        // clean-up
        $flags = $this->getOption('pretty') ? JSON_PRETTY_PRINT : 0;
        file_put_contents($this->outputfile, json_encode($buffer, $flags));
    }
}
