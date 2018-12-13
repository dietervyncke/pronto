<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Input\ConsoleInput;

class ConsoleInputTest extends TestCase
{
	private $input;

	public function setup()
	{
		$this->input = new ConsoleInput();
	}

	public function testWriteToConsole()
	{
		$this->assertNull($this->input->write('hi there!'));
	}
}