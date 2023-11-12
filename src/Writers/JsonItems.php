<?php
namespace Generators\Writers;

use Generators\AbstractWriter;
use Generators\WithOptions;

class Json extends AbstractWriter
{
    use WithOptions;

    public static $default_options = [
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
        $fh = fopen($this->outputfile, 'w');
        if (!$fh) {
            throw new \Exception('Could not create output file: ' . $this->outputfile);
        }
        
        // repeat
        while (($data = yield) !== null) {
            fwrite($fh, json_encode($data) . PHP_EOL);
        }
        
        // clean-up
        fclose($fh);
    }
}
