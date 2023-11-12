<?php
namespace Generators\Readers;

use Generators\AbstractReader;
use Generators\WithOptions;

class CsvReader extends AbstractReader
{
    use WithOptions;

    public static $default_options = array (
        'with_headers' => true,
        'separator' => ',',
        'delimiter' => '"',
        'escape' => '"',
        'stop_on_blank' => false,
    );
    
    private $inputfile = false;
    
    public function __construct($inputfile, $options = [])
    {
        ini_set("auto_detect_line_endings", true);
        $this->inputfile = $inputfile;
        $this->setOptions($options);
        
        if ((!is_resource($this->inputfile)) && (!file_exists($this->inputfile))) {
            throw new \Exception('Input file does not exist: ' . $this->inputfile);
        }
    }
    
    private static function isEmptyArray($array)
    {
        return count($array) == 1 && ( $array[0] === null || trim($array[0]) === '' );
    }
    
    protected function readerGenerator(): \Generator
    {
        return $this->getLines();
    }
    
    private function getLines()
    {
        $fh = is_resource($this->inputfile) ? $this->inputfile : @fopen($this->inputfile, 'r');
        if (!$fh) {
            throw new \Exception('Could not open input file ' . $this->inputfile);
        }
        
        $headers = false;

        while (!feof($fh)) {
            if ($headers === false && $this->getOption('with_headers')) {
                $headers = fgetcsv($fh, 0, $this->getOption('separator'), $this->getOption('delimiter'), $this->getOption('escape'));
                if (!$headers || self::isEmptyArray($headers)) {
                    if (feof($fh)) {
                        break;
                    }
                    throw new \Exception('Empty headers line in file ' . $this->inputfile);
                }
            }
            
            $row = fgetcsv($fh, 0, $this->getOption('separator'), $this->getOption('delimiter'), $this->getOption('escape'));
            if (!$row || self::isEmptyArray($row)) {
                if ($this->getOption('stop_on_blank')) {
                    break;
                }
                continue;
            }
            yield $this->getOption('with_headers') ? array_combine($headers, $row) : $row;
        }
        
        fclose($fh);
    }
}
