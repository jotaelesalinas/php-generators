<?php
namespace Generators\Readers;

use Generators\AbstractReader;

class Directory extends AbstractReader
{
    private $path;
    private $glob;
    private $recursive;
    
    public function __construct($path, $glob = '*', $recursive = false)
    {
        $this->path = $path;
        $this->glob = $glob;
        $this->recursive = $recursive;
    }
    
    private static function rglob($path, $pattern, $recursive)
    {
        $search = $path . DIRECTORY_SEPARATOR . $pattern;
        yield from glob($search);

        if ($recursive) {
            foreach (glob($path . '/*',  GLOB_ONLYDIR) as $entry) {
                $new_dir = $path . DIRECTORY_SEPARATOR . $entry;
                yield from static::rglob($new_dir, $pattern, true);
            }
        }
    }

    protected function readerGenerator(): \Generator
    {
        yield from static::rglob($this->path, $this->glob, $this->recursive);
    }
}
