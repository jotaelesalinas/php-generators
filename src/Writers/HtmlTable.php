<?php
namespace JLSalinas\RWGen\Writers;

use JLSalinas\RWGen\Writer;

class HtmlTable extends Writer {
    public static $default_options = array (
        'overwrite' => false,
        'transformer' => null,  // a function that accepts the key and the value of the item to write
    );
    
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

        $fh = fopen($this->outputfile, 'w');
        if ( !$fh ) {
            throw new \Exception('Could not open output file: ' . $this->outputfile);
        }

        fwrite($fh, '<table border="1">' . "\n");
        
        // repeat

        $numrow = 0;
        while ( ($data = yield) !== null ) {
            $numrow += 1;
            
            if ( $numrow == 1 ) {
                $lines = [];
                $lines[] = '<tr>';
                foreach (array_keys($data) as $k) {
                    $lines[] = '<th>' . htmlentities($k) . '</th>';
                }
                $lines[] = '</tr>';
                fwrite($fh, implode("\n", $lines) . "\n");
            }
            
            $lines = [];
            $lines[] = '<tr>';
            foreach ($data as $k => $v) {
            	$lines[] = '<td>' . ( $k != 'picture_url' ? htmlentities($v) : ($v ? '<img src="' . htmlentities($v) . '" />' : '')) . '</td>';
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
