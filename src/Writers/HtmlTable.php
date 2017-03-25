<?php
namespace JLSalinas\RWGen\Writers;

use JLSalinas\RWGen\Writer;

class HtmlTable extends Writer {
    public static $default_options = [
        'overwrite' => false,
        // a function that accepts the key and the value of the item to write and returns what to output (correctly escaped)
        'transform' => false,
        'border' => '1',
        'padding' => '4',
        'spacing' => '0',
    ];
    
    private $outputfile = null;
    
    public function __construct ($outputfile, $options = array()) {
        $this->outputfile = $outputfile;
        $this->setOptions($options);
        
        if ( !$this->getOption('overwrite') && file_exists($outputfile) ) {
            throw new \Exception('Output file already exists: ' . $this->outputfile);
        }
    }

    private function saveLines () {
        // prepare

        $func = $this->getOption('transform') ? $this->getOption('transform') : function ($k, $v) { return htmlentities($v); };
        
        $fh = fopen($this->outputfile, 'w');
        if ( !$fh ) {
            throw new \Exception('Could not open output file: ' . $this->outputfile);
        }

        fwrite($fh, '<table ' .
            'border="' . $this->getOption('border') . '" ' .
            'cellpadding="' . $this->getOption('padding') . '" ' .
            'cellspacing="' . $this->getOption('spacing') . '" ' .
            '>' . "\n");
        
        // repeat

        $numrow = 0;
        while ( ($data = yield) !== null ) {
            $numrow += 1;
            
            if ( $numrow == 1 ) {
                $lines = [];
                $lines[] = '<tr>';
                foreach (array_keys($data) as $k) {
                    $lines[] = "\t" . '<th>' . htmlentities($k) . '</th>';
                }
                $lines[] = '</tr>';
                fwrite($fh, implode("\n", $lines) . "\n");
            }
            
            $lines = [];
            $lines[] = '<tr>';
            foreach ($data as $k => $v) {
            	$lines[] = "\t" . '<td>' . $func($k, $v) . '</td>';
            }
            $lines[] = '</tr>';
            fwrite($fh, implode("\n", $lines) . "\n");
        }
        
        // clean-up

        fwrite($fh, '</table>' . "\n");
        fclose($fh);
    }
    
    protected function outputGenerator () {
        return $this->saveLines();
    }
}
