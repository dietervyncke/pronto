<?php

namespace Tests\Pronto;

use lib\Compiler;
use lib\Node\RootNode;
use lib\Node\TextNode;
use PHPUnit\Framework\TestCase;

class CompilerTest extends TestCase
{
	private $compiler;

	public function setup()
	{
		$this->compiler = new Compiler();
	}

	public function testCompileShouldReturnStringType()
	{
		$this->assertIsString($this->compiler->compile(new TextNode( 'dummy text' )));
	}

	public function testCompileInitialBodyValue()
	{
		$this->compiler->writeBody( 'some predefined text' );
		$this->assertEquals('some predefined text', $this->compiler->compile(new RootNode()));
	}

	public function testCompileInitialHeadValue()
	{
		$this->compiler->writeHead( 'some predefined text (head)' );
		$this->assertEquals('some predefined text (head)', $this->compiler->compile(new RootNode()));
	}

	public function testCompileShouldReturnCorrectString()
	{
		$this->assertEquals('<?php $env->write(\'text node text\'); ?>', $this->compiler->compile(new TextNode('text node text')));
	}

	public function testCompileShouldReturnEmptyString()
	{
		$this->assertEquals('', $this->compiler->compile(new RootNode()));
	}
}