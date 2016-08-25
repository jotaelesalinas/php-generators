<?php
namespace JLSalinas\RWGen\Writers;

use JLSalinas\RWGen\Writer;

// https://gist.github.com/johanmeiring/2894568
if (!function_exists('str_putcsv')) {
    function str_putcsv($input, $delimiter = ',', $enclosure = '"')
    {
        // Open a memory "file" for read/write...
        $fp = fopen('php://temp', 'r+');
        // ... write the $input array to the "file" using fputcsv()...
        fputcsv($fp, $input, $delimiter, $enclosure);
        // ... rewind the "file" so we can read what we just wrote...
        rewind($fp);
        // ... read the entire line into a variable...
        $data = fread($fp, 1048576);
        // ... close the "file"...
        fclose($fp);
        // ... and return the $data to the caller, with the trailing newline from fgets() removed.
        return rtrim($data, "\n");
    }
}

class Csv extends Writer
{
    public static $defaults = array (
        'overwrite' => false,
        'with_headers' => true,
        'separator' => ',',
        'delimiter' => '"',
        'escape' => '"',
        'buffer' => 1000000,
        'split_lines' => 0,
    );
    
    private $outputfile = false;
    
    public function __construct($outputfile, $options = array())
    {
        $this->outputfile = $outputfile;
        $this->setOptions($options);
        
        if (!$this->getOption('overwrite')) {
            if (file_exists($outputfile)) {
                throw new \Exception('Output file already exists: ' . $this->outputfile);
            }
        }
    }
    
    private function makeCsvRow($row, $numrow)
    {
        $str = str_putcsv($row, $this->getOption('separator'), $this->getOption('delimiter')) . "\n";
        if ($numrow == 1) {
            $str = str_putcsv(array_keys($row), $this->getOption('separator'), $this->getOption('delimiter')) . "\n" . $str;
        }
        return $str;
    }
    
    protected function innerGenerator($filename)
    {
        $fh = fopen($filename, 'w');
        if (!$fh) {
            throw new \Exception('Could not open output file ' . $filename);
        }
        
        $numline = 0;
        do {
            $row = yield;
            
            if ($row !== null) {
                $numline += 1;
                $line = $this->makeCsvRow($row, $numline);
                if ($line !== null && $line !== false) {
                    fwrite($fh, $line);
                }
            }
        } while ($row !== null);
        
        fclose($fh);
    }
    
    protected function outputGenerator()
    {
        $numfile = 1;
        $numlines = 0;
        
        $gen = $this->innerGenerator($this->outputfile);
        do {
            $row = yield;
            $gen->send($row);
            if ($this->getOption('split_lines') && ($numlines > 0) && ($numlines % $this->getOption('split_lines') == 0)) {
                $gen->send(null);
                $numfile += 1;
                $gen = $this->innerGenerator($this->outputfile . '.' . $numfile . '.csv');
            }
            $numlines += 1;
        } while ($row !== null);
    }
}
