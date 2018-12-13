<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\TextNode;

class TextNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue(' ');
		$this->checkIfParserReturnsTrue('     ');
		$this->checkIfParserReturnsTrue('dummy text');
		$this->checkIfParserReturnsTrue('dummy text,, some "more"');
		$this->checkIfParserReturnsTrue('\' more text');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{');
//		$this->checkIfParserReturnsFalse(5);
	}

	private function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);

		$this->assertTrue(TextNode::parse($parser));
	}

	private function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);

		$this->assertFalse(TextNode::parse($parser));
	}

	private function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);

		$compiler = new Compiler();
//		$this->assertEquals($compiled, $compiler->compile($parser->parse()));
	}
}