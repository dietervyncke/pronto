<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Node\ParameterNode;
use Pronto\Token;

class ParameterNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ 5, 10, 1 }}');
		$this->checkIfParserReturnsTrue('{{ 10 }}');
		$this->checkIfParserReturnsTrue('{{ "my string" }}');
		$this->checkIfParserReturnsTrue('{{ "my string", 5, 10 }}');
		$this->checkIfParserReturnsTrue('{{ 10, \'test\' }}');
		$this->checkIfParserReturnsTrue('{{ 10 , 100, .75 }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ identifier }}');
		$this->checkIfParserReturnsFalse('{{ /repeat }}');
		$this->checkIfParserReturnsFalse('{{ if }}');
		$this->checkIfParserReturnsFalse('{{ /if }}');
	}

	public function testParsingThrowsSyntaxErrorWhenValueIsNotExpression()
	{
		$this->checkIfParserThrowsSyntaxError('{{ 10, identifier }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ 10, 5 }}', '[10,5,]');
		$this->checkIfCompilerGivesExactResult('{{ "string",.75 , 50,1 }}', '[\'string\',.75,50,1,]');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(ParameterNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(ParameterNode::parse($parser));
	}

	public function checkIfParserThrowsSyntaxError($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->expectException(SyntaxError::class);
		ParameterNode::parse($parser);
	}

	public function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		ParameterNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}