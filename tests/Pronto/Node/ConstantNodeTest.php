<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\ConstantNode;
use Pronto\Token;

class ConstantNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ TRUE }}');
		$this->checkIfParserReturnsTrue('{{ FALSE }}');
		$this->checkIfParserReturnsTrue('{{ ZERO }}');
		$this->checkIfParserReturnsTrue('{{ ONE }}');
		$this->checkIfParserReturnsTrue('{{ NULL }}');
		$this->checkIfParserReturnsTrue('{{ PI }}');
		$this->checkIfParserReturnsTrue('{{ E }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ true }}');
		$this->checkIfParserReturnsFalse('{{ false }}');
		$this->checkIfParserReturnsFalse('{{ 0 }}');
		$this->checkIfParserReturnsFalse('{{ ZER }}');
		$this->checkIfParserReturnsFalse('{{ ON }}');
		$this->checkIfParserReturnsFalse('{{ null }}');
		$this->checkIfParserReturnsFalse('{{ 1 }}');
		$this->checkIfParserReturnsFalse('{{ Ù   }}');
		$this->checkIfParserReturnsFalse('{{ K }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ ZERO }}', '0');
		$this->checkIfCompilerGivesExactResult('{{ ONE }}', '1');
		$this->checkIfCompilerGivesExactResult('{{ TRUE }}', 'true');
		$this->checkIfCompilerGivesExactResult('{{ FALSE }}', 'false');
		$this->checkIfCompilerGivesExactResult('{{ NULL }}', 'null');
		$this->checkIfCompilerGivesExactResult('{{ PI }}', '3.1415927');
		$this->checkIfCompilerGivesExactResult('{{ E }}', '2.71828');
	}

	private function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(ConstantNode::parse($parser));
	}

	private function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(ConstantNode::parse($parser));
	}

	private function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		ConstantNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}