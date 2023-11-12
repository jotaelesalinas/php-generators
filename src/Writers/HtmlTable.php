<?php
namespace Generators\Writers;

use Generators\AbstractWriter;
use Generators\WithOptions;

class HtmlTable extends AbstractWriter
{
    use WithOptions;

    public static $default_options = [
        'overwrite' => false,
        'transform' => false,
        'border' => '1',
        'padding' => '4',
        'spacing' => '0',
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
        $transform = $this->getOption('transform') ? $this->getOption('transform') : function ($v) {
            return htmlentities($v);
        };
        
        // prepare
        $fh = fopen($this->outputfile, 'w');
        if (!$fh) {
            throw new \Exception('Could not create output file: ' . $this->outputfile);
        }

        fwrite($fh, '<table ' .
            'border="' . $this->getOption('border') . '" ' .
            'cellpadding="' . $this->getOption('padding') . '" ' .
            'cellspacing="' . $this->getOption('spacing') . '" ' .
            '>' . PHP_EOL);
        
        // repeat
        $numrow = 0;
        while (($data = yield) !== null) {
            $numrow += 1;
            
            if ($numrow == 1) {
                $lines = array_map(fn($k) => '<th>' . htmlentities($k) . '</th>', array_keys($data));
                array_unshift($lines, '<tr>');
                array_push($lines, '</tr>');
                fwrite($fh, implode(PHP_EOL . "\t", $lines) . PHP_EOL);
            }
            
            $lines = array_map(fn($k) => '<td>' . $transform($k) . '</td>', $data);
            array_unshift($lines, '<tr>');
            array_push($lines, '</tr>');
            fwrite($fh, implode(PHP_EOL . "\t", $lines) . PHP_EOL);
        }
        
        // clean-up
        fwrite($fh, '</table>' . PHP_EOL);
        fclose($fh);
    }
}
