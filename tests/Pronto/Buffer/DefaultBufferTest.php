<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Buffer\DefaultBuffer;

class DefaultBufferTest extends TestCase
{
	private $contents;

	public function setup()
	{
		$this->contents = new DefaultBuffer();
	}

	public function testReadFromEmptyBufferReturnsEmptyString()
	{
		$this->assertEquals('', $this->contents->read());
	}

	public function testWriteToBufferReturnString()
	{
		$this->contents->write('dummy text');
		$this->assertIsString($this->contents->read());
	}

	public function testClearBuffer()
	{
		$this->contents->write('dummy text');
		$this->contents->clear();
		$this->assertEquals('', $this->contents->read());
	}

	public function testMultipleWriting()
	{
		$this->assertIsString($this->contents->read());
		$this->contents->write('dummy text. ');
		$this->contents->write(' Some more text');
		$this->assertIsString($this->contents->read());
		$this->assertEquals('dummy text.  Some more text', $this->contents->read());
	}
}