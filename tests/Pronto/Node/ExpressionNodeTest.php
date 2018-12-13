<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\ExpressionNode;
use Pronto\Token;

class ExpressionNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ if ?=global_var + ?-my_local_var }}');
		$this->checkIfParserReturnsTrue('{{ if "hi there" + 5 }}');
		$this->checkIfParserReturnsTrue('{{ if 5 - 3 }}');
		$this->checkIfParserReturnsTrue('{{ if ?-my_local_var }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ if undefined += "test" }}');
		$this->checkIfParserReturnsFalse('{{ if false }}');
		$this->checkIfParserReturnsFalse('{{ if   }}');
		$this->checkIfParserReturnsFalse('{{ if /if  }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ ?=global_var }}', '$env->getGlobalVariable( \'global_var\' )');
		$this->checkIfCompilerGivesExactResult('{{ ?-local_var }}', '$env->getLocalVariable( \'local_var\' )');
		$this->checkIfCompilerGivesExactResult('{{ "string" }}', '\'string\'');
		$this->checkIfCompilerGivesExactResult('{{ 5 }}', '5');
		$this->checkIfCompilerGivesExactResult('{{ 5 - 3 }}', '5-3');
		$this->checkIfCompilerGivesExactResult('{{ 5 +   "string" }}', '5+\'string\'');
		$this->checkIfCompilerGivesExactResult('{{ ?=global_var + ?-my_local_var }}', '$env->getGlobalVariable( \'global_var\' )+$env->getLocalVariable( \'my_local_var\' )');
	}

	private function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		$parser->skip(Token::T_IDENT);

		$this->assertTrue(ExpressionNode::parse($parser));
	}

	private function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		$parser->skip(Token::T_IDENT);

		$this->assertFalse(ExpressionNode::parse($parser));
	}

	private function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		ExpressionNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}