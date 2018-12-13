<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\ConditionNode;
use Pronto\Token;

class ConditionNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ if 5+6 equals 10 }}');
		$this->checkIfParserReturnsTrue('{{ if 5 equals 10 }}');
		$this->checkIfParserReturnsTrue('{{ if 5 }}' );
		$this->checkIfParserReturnsTrue('{{ if equals }}' );
		$this->checkIfParserReturnsTrue('{{ if 5+6+4 equals 50 }}dummy text{{ /if }}');
		$this->checkIfParserReturnsTrue('{{ if 5 q 6 }}');
	}

//	public function testParsingReturnsFalse()
//	{
//	}

//	public function testCompilingResults()
//	{
//	}

	private function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		$parser->skip(Token::T_IDENT);

		$this->assertTrue(ConditionNode::parse($parser));
	}

	private function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		$parser->skip(Token::T_IDENT);

		$this->assertFalse(ConditionNode::parse($parser));
	}

	private function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		$parser->skip(Token::T_IDENT);
		ConditionNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}