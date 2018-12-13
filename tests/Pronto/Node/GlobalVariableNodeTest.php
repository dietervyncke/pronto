<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\GlobalVariableNode;
use Pronto\Token;

class GlobalVariableNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ ?=my_var_snake_case }}');
		$this->checkIfParserReturnsTrue('{{ ?=myvarlowercase }}');
		$this->checkIfParserReturnsTrue('{{ ?=myVarCamelCase }}');
		$this->checkIfParserReturnsTrue('{{ ?= }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ ?-myvar }}');
		$this->checkIfParserReturnsFalse('{{ ? =myvar }}');
		$this->checkIfParserReturnsFalse('{{ ? = myvar }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ ?=test }}', '$env->getGlobalVariable(\'test\')');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(GlobalVariableNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(GlobalVariableNode::parse($parser));
	}

	public function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		GlobalVariableNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}