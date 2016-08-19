<?php
namespace JLSalinas\RWGen\Tests;

use JLSalinas\RWGen\Readers\Csv;

class CsvReaderTest extends \PHPUnit_Framework_TestCase {
    static protected function createTmp ($text) {
        $fh = tmpfile();
        if ( !$fh ) {
            return false;
        }
        fwrite($fh, is_array($text) ? implode("\n", $text) : $text);
        fseek($fh, 0);
        return $fh;
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Input file does not exist: adsfasdf.csv
     */
    public function testFileDoesNotExistThrowsException () {
        $reader = new Csv('adsfasdf.csv');
    }
    
    public function testEmptyFileDoesntReturnAnything() {
        $fh = self::createTmp('');
        $reader = new Csv($fh);
        $count = 0;
        foreach ( $reader as $row ) {
            $count  += 1;
        }
        $this->assertEquals(0, $count);
        
        $fh = self::createTmp('');
        $reader = new Csv($fh, [ 'with_headers' => false ]);
        $count = 0;
        foreach ( $reader as $row ) {
            $count  += 1;
        }
        $this->assertEquals(0, $count);
    }

    public function testReturnsCorrectNumberOfLines() {
        $data_0rows = [
            'field_1,field_2,field_3,field_4',
        ];
        
        $fh = self::createTmp($data_0rows);
        $reader = new Csv($fh);
        $count = 0;
        foreach ( $reader as $row ) {
            $count  += 1;
        }
        $this->assertEquals(0, $count);
        
        $fh = self::createTmp($data_0rows);
        $reader = new Csv($fh, [ 'with_headers' => false ]);
        $count = 0;
        foreach ( $reader as $row ) {
            $count  += 1;
        }
        $this->assertEquals(1, $count);
    
        $data_5rows = [
            'field_1,field_2,field_3,field_4',
            'a1,b1,c1,d1',
            'a2,b2,c2,d2',
            'a3,b3,c3,d3',
            'a4,b4,c4,d4',
            'a5,b5,c5,d5',
        ];
        
        $fh = self::createTmp($data_5rows);
        $reader = new Csv($fh);
        $count = 0;
        foreach ( $reader as $row ) {
            $count  += 1;
        }
        $this->assertEquals(5, $count);
        
        $fh = self::createTmp($data_5rows);
        $reader = new Csv($fh, [ 'with_headers' => false ]);
        $count = 0;
        foreach ( $reader as $row ) {
            $count  += 1;
        }
        $this->assertEquals(6, $count);
    }
    
	public function testCommasNoQuotes () {
        $data = [
            'h1,h2,h3,h4',
            'a1,b1,c1,d1',
            'a2,b2,c2,d2',
        ];
        
        $fh = self::createTmp($data);
        $reader = new Csv($fh);
        foreach ( $reader as $k => $row ) {
			$keys = array_keys($row);
			$this->assertEquals(4, count($keys));
			$this->assertEquals('h1', $keys[0]);
			$this->assertEquals('h2', $keys[1]);
			$this->assertEquals('h3', $keys[2]);
			$this->assertEquals('h4', $keys[3]);
			
			if ( $k == 0 ) {
				$this->assertEquals('a1', $row['h1']);
				$this->assertEquals('b1', $row['h2']);
				$this->assertEquals('c1', $row['h3']);
				$this->assertEquals('d1', $row['h4']);
			} else if ( $k == 1 ) {
				$this->assertEquals('a2', $row['h1']);
				$this->assertEquals('b2', $row['h2']);
				$this->assertEquals('c2', $row['h3']);
				$this->assertEquals('d2', $row['h4']);
			} else {
				throw new Exception('Wrong number of lines.');
			}
        }
		
        $fh = self::createTmp($data);
        $reader = new Csv($fh, [ 'with_headers' => false ]);
        foreach ( $reader as $k => $row ) {
			$keys = array_keys($row);
			$this->assertEquals(4, count($keys));
			$this->assertEquals(0, $keys[0]);
			$this->assertEquals(1, $keys[1]);
			$this->assertEquals(2, $keys[2]);
			$this->assertEquals(3, $keys[3]);
			
			if ( $k == 0 ) {
				$this->assertEquals('h1', $row[0]);
				$this->assertEquals('h2', $row[1]);
				$this->assertEquals('h3', $row[2]);
				$this->assertEquals('h4', $row[3]);
			} else if ( $k == 1 ) {
				$this->assertEquals('a1', $row[0]);
				$this->assertEquals('b1', $row[1]);
				$this->assertEquals('c1', $row[2]);
				$this->assertEquals('d1', $row[3]);
			} else if ( $k == 2 ) {
				$this->assertEquals('a2', $row[0]);
				$this->assertEquals('b2', $row[1]);
				$this->assertEquals('c2', $row[2]);
				$this->assertEquals('d2', $row[3]);
			} else {
				throw new Exception('Wrong number of lines.');
			}
        }
		
	}
	
	public function testCommasQuotes () {
        $data = [
            '"h1","h2","h3","h4"',
            '"a1","b1","c1","d1"',
            '"a2","b2","c2","d2"',
        ];
        
        $fh = self::createTmp($data);
        $reader = new Csv($fh);
        foreach ( $reader as $k => $row ) {
			$keys = array_keys($row);
			$this->assertEquals(4, count($keys));
			$this->assertEquals('h1', $keys[0]);
			$this->assertEquals('h2', $keys[1]);
			$this->assertEquals('h3', $keys[2]);
			$this->assertEquals('h4', $keys[3]);
			
			if ( $k == 0 ) {
				$this->assertEquals('a1', $row['h1']);
				$this->assertEquals('b1', $row['h2']);
				$this->assertEquals('c1', $row['h3']);
				$this->assertEquals('d1', $row['h4']);
			} else if ( $k == 1 ) {
				$this->assertEquals('a2', $row['h1']);
				$this->assertEquals('b2', $row['h2']);
				$this->assertEquals('c2', $row['h3']);
				$this->assertEquals('d2', $row['h4']);
			} else {
				throw new Exception('Wrong number of lines.');
			}
        }
		
        $fh = self::createTmp($data);
        $reader = new Csv($fh, [ 'with_headers' => false ]);
        foreach ( $reader as $k => $row ) {
			$keys = array_keys($row);
			$this->assertEquals(4, count($keys));
			$this->assertEquals(0, $keys[0]);
			$this->assertEquals(1, $keys[1]);
			$this->assertEquals(2, $keys[2]);
			$this->assertEquals(3, $keys[3]);
			
			if ( $k == 0 ) {
				$this->assertEquals('h1', $row[0]);
				$this->assertEquals('h2', $row[1]);
				$this->assertEquals('h3', $row[2]);
				$this->assertEquals('h4', $row[3]);
			} else if ( $k == 1 ) {
				$this->assertEquals('a1', $row[0]);
				$this->assertEquals('b1', $row[1]);
				$this->assertEquals('c1', $row[2]);
				$this->assertEquals('d1', $row[3]);
			} else if ( $k == 2 ) {
				$this->assertEquals('a2', $row[0]);
				$this->assertEquals('b2', $row[1]);
				$this->assertEquals('c2', $row[2]);
				$this->assertEquals('d2', $row[3]);
			} else {
				throw new Exception('Wrong number of lines.');
			}
        }
		
	}
	
	public function testCommasSomeQuotes () {
        $data = [
            'h1,"h2","h3",h4',
            '"a1",b1,"c1","d1"',
            '"a2","b2",c2,"d2"',
        ];
        
        $fh = self::createTmp($data);
        $reader = new Csv($fh);
        foreach ( $reader as $k => $row ) {
			$keys = array_keys($row);
			$this->assertEquals(4, count($keys));
			$this->assertEquals('h1', $keys[0]);
			$this->assertEquals('h2', $keys[1]);
			$this->assertEquals('h3', $keys[2]);
			$this->assertEquals('h4', $keys[3]);
			
			if ( $k == 0 ) {
				$this->assertEquals('a1', $row['h1']);
				$this->assertEquals('b1', $row['h2']);
				$this->assertEquals('c1', $row['h3']);
				$this->assertEquals('d1', $row['h4']);
			} else if ( $k == 1 ) {
				$this->assertEquals('a2', $row['h1']);
				$this->assertEquals('b2', $row['h2']);
				$this->assertEquals('c2', $row['h3']);
				$this->assertEquals('d2', $row['h4']);
			} else {
				throw new Exception('Wrong number of lines.');
			}
        }
		
        $fh = self::createTmp($data);
        $reader = new Csv($fh, [ 'with_headers' => false ]);
        foreach ( $reader as $k => $row ) {
			$keys = array_keys($row);
			$this->assertEquals(4, count($keys));
			$this->assertEquals(0, $keys[0]);
			$this->assertEquals(1, $keys[1]);
			$this->assertEquals(2, $keys[2]);
			$this->assertEquals(3, $keys[3]);
			
			if ( $k == 0 ) {
				$this->assertEquals('h1', $row[0]);
				$this->assertEquals('h2', $row[1]);
				$this->assertEquals('h3', $row[2]);
				$this->assertEquals('h4', $row[3]);
			} else if ( $k == 1 ) {
				$this->assertEquals('a1', $row[0]);
				$this->assertEquals('b1', $row[1]);
				$this->assertEquals('c1', $row[2]);
				$this->assertEquals('d1', $row[3]);
			} else if ( $k == 2 ) {
				$this->assertEquals('a2', $row[0]);
				$this->assertEquals('b2', $row[1]);
				$this->assertEquals('c2', $row[2]);
				$this->assertEquals('d2', $row[3]);
			} else {
				throw new Exception('Wrong number of lines.');
			}
        }
		
	}
	
	public function testCommasDoubleQuotes () {
        $data = [
            '"h""1","h2","h3","h4"',
            'a1,"b""1","""c1",d1',
            'a2,b2,"c2""",d2',
        ];
        
        $fh = self::createTmp($data);
        $reader = new Csv($fh);
        foreach ( $reader as $k => $row ) {
			$keys = array_keys($row);
			$this->assertEquals(4, count($keys));
			$this->assertEquals('h"1', $keys[0]);
			$this->assertEquals('h2', $keys[1]);
			$this->assertEquals('h3', $keys[2]);
			$this->assertEquals('h4', $keys[3]);
			
			if ( $k == 0 ) {
				$this->assertEquals('a1', $row['h"1']);
				$this->assertEquals('b"1', $row['h2']);
				$this->assertEquals('"c1', $row['h3']);
				$this->assertEquals('d1', $row['h4']);
			} else if ( $k == 1 ) {
				$this->assertEquals('a2', $row['h"1']);
				$this->assertEquals('b2', $row['h2']);
				$this->assertEquals('c2"', $row['h3']);
				$this->assertEquals('d2', $row['h4']);
			} else {
				throw new Exception('Wrong number of lines.');
			}
        }
		
        $fh = self::createTmp($data);
        $reader = new Csv($fh, [ 'with_headers' => false ]);
        foreach ( $reader as $k => $row ) {
			$keys = array_keys($row);
			$this->assertEquals(4, count($keys));
			$this->assertEquals(0, $keys[0]);
			$this->assertEquals(1, $keys[1]);
			$this->assertEquals(2, $keys[2]);
			$this->assertEquals(3, $keys[3]);
			
			if ( $k == 0 ) {
				$this->assertEquals('h"1', $row[0]);
				$this->assertEquals('h2', $row[1]);
				$this->assertEquals('h3', $row[2]);
				$this->assertEquals('h4', $row[3]);
			} else if ( $k == 1 ) {
				$this->assertEquals('a1', $row[0]);
				$this->assertEquals('b"1', $row[1]);
				$this->assertEquals('"c1', $row[2]);
				$this->assertEquals('d1', $row[3]);
			} else if ( $k == 2 ) {
				$this->assertEquals('a2', $row[0]);
				$this->assertEquals('b2', $row[1]);
				$this->assertEquals('c2"', $row[2]);
				$this->assertEquals('d2', $row[3]);
			} else {
				throw new Exception('Wrong number of lines.');
			}
        }
		
	}
}
