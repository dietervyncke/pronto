<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;

class ConditionNodeTest extends TestCase
{
//	public function testParsingReturnsTrue()
//	{
//
//	}
//
//	public function testParsingReturnsFalse()
//	{
//
//	}
//
//	public function testCompilingResults()
//	{
//
//	}

	private function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);

//		$this->assertTrue(TextNode::parse($parser));
	}

	private function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);

//		$this->assertFalse(TextNode::parse($parser));
	}

	private function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);

		$compiler = new Compiler();
		$this->assertEquals($compiled, $compiler->compile($parser->parse()));
	}
}