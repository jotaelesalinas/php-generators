<?php
namespace JLSalinas\RWGen\Tests;

use JLSalinas\RWGen\WithOptions;

class WithOptionsStub_NoDefs {
	use WithOptions;
}

class WithOptionsStub_Defs {
	use WithOptions;
	
	public static $default_options = array (
		'key_true' => true,
		'key_false' => false,
		'key_closure' => null, // cannot assign anonymous function directly in a constant declaration
		'key_string' => 'asdf',
		'key_number' => 7,
		'key_null' => null,
	);
}

class WithOptionsTest extends \PHPUnit_Framework_TestCase {
    public function testDefaults () {
        $this->assertEquals(false, isset(WithOptionsStub_NoDefs::$default_options));
		$defs = WithOptionsStub_NoDefs::getDefaults();
        $this->assertSame(array(), $defs);
		
        // fix it now
		WithOptionsStub_Defs::$default_options['key_closure'] = function () {};
        
        $this->assertEquals(true, isset(WithOptionsStub_Defs::$default_options));
        $this->assertEquals(6, count(WithOptionsStub_Defs::$default_options));
        $this->assertSame(true, WithOptionsStub_Defs::$default_options['key_true']);
        $this->assertSame(false, WithOptionsStub_Defs::$default_options['key_false']);
        $this->assertSame(null, WithOptionsStub_Defs::$default_options['key_null']);
        $this->assertSame(true, is_callable(WithOptionsStub_Defs::$default_options['key_closure']));
        $this->assertSame(7, WithOptionsStub_Defs::$default_options['key_number']);
        $this->assertSame('asdf', WithOptionsStub_Defs::$default_options['key_string']);
        $this->assertSame(false, isset(WithOptionsStub_Defs::$default_options['key_float']));
		
		$defs = WithOptionsStub_Defs::getDefaults();
		$this->assertSame(
			implode(', ', array_keys($defs)),
			implode(', ', array_keys(WithOptionsStub_Defs::$default_options)) );
		foreach ( WithOptionsStub_Defs::$default_options as $k => $v ) {
			$this->assertSame($v, $defs[$k]);
		}
	}
	
	public function testChangeValues () {
		$defs = WithOptionsStub_Defs::getDefaults();
        $this->assertSame(7, WithOptionsStub_Defs::$default_options['key_number']);
        $this->assertSame(7, $defs['key_number']);
		
		$obj = new WithOptionsStub_Defs();
        $this->assertSame(7, $obj->getOption('key_number'));
		
        WithOptionsStub_Defs::$default_options['key_number'] = 6;
        $this->assertSame(7, $obj->getOption('key_number'));
		
		$defs = WithOptionsStub_Defs::getDefaults();
        $this->assertSame(6, WithOptionsStub_Defs::$default_options['key_number']);
        $this->assertSame(6, $defs['key_number']);
		
		$obj = new WithOptionsStub_Defs();
        $this->assertSame(6, $obj->getOption('key_number'));
	}
}
