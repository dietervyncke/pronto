<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\LogicalOperatorNode;
use Pronto\Token;

class LogicalOperatorNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ if equals }}');
		$this->checkIfParserReturnsTrue('{{ if   equals }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ if eqals }}');
		$this->checkIfParserReturnsFalse('{{ if "equals" }}');
		$this->checkIfParserReturnsFalse('{{ if === }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ if equals }}', ' === ');
	}

	private function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		$parser->skip(Token::T_IDENT);

		$this->assertTrue(LogicalOperatorNode::parse($parser));
	}

	private function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		$parser->skip(Token::T_IDENT);

		$this->assertFalse(LogicalOperatorNode::parse($parser));
	}

	private function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		$parser->skip(Token::T_IDENT);
		LogicalOperatorNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}