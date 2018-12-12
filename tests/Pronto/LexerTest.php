<?php

namespace Tests\Pronto;

use lib\Lexer;
use lib\TokenStream;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
	private $lexer;

	public function setup()
	{
		$this->lexer = new Lexer();
	}

	public function testTokenizeShouldReturnInstanceOfTokenStream()
	{
		$this->tokenizeShouldReturnInstanceOfTokenStream(' ');
		$this->tokenizeShouldReturnInstanceOfTokenStream('some dummy text');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{');
		$this->tokenizeShouldReturnInstanceOfTokenStream('}}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ "dummy" }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ ?=global_var }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ ?-private_var }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ var ?-private_var is 10 }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ repeat }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ repeat( "Want to repeat something?" ) }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ repeat( "Want to repeat something?" ) }} hi there {{ /repeat }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ /repeat }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ if ?=var is false }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ /if }}');
	}

	public function testTokenValueTest()
	{
		$this->tokenValueTest('{{', '');
		$this->tokenValueTest('}}', '}}');
		$this->tokenValueTest('{{}}', '');
		$this->tokenValueTest('{{ 5 }}', 5, 1);
		$this->tokenValueTest('{{ ?=global_var }}', 'global_var', 1);
		$this->tokenValueTest('{{ ?-private_var }}', 'private_var', 1);
		$this->tokenValueTest('{{ ?-global_var( "option 1", "option 2" ) }}', 'option 1', 3);
		$this->tokenValueTest('{{ ?-global_var( "option 1" ) }}', '(', 2);
		$this->tokenValueTest('3', 3);
	}

	private function tokenizeShouldReturnInstanceOfTokenStream($input)
	{
		$this->assertInstanceOf(TokenStream::class, $this->lexer->tokenize($input));
	}

	private function tokenValueTest($input, $value, $position = 0)
	{
		$stream = $this->lexer->tokenize($input);
		$this->assertEquals($value, $stream->getToken($position)->getValue(), 'Value of token does not match '.$value);
	}
}