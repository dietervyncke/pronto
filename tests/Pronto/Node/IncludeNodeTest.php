<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Node\IncludeNode;
use Pronto\Token;

class IncludeNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ include "myfile.tpl" }}');
		$this->checkIfParserReturnsTrue('{{ include "test" }}');
		$this->checkIfParserReturnsTrue('{{ include 5 }}');
		$this->checkIfParserReturnsTrue('{{ include 45 + 10 }}');
		$this->checkIfParserReturnsTrue('{{ include ?-local }}');
		$this->checkIfParserReturnsTrue('{{ include ?=global }}');
		$this->checkIfParserReturnsTrue('{{ include ?=global + ?-local }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ inc }}');
		$this->checkIfParserReturnsFalse('{{ includ }}');
		$this->checkIfParserReturnsFalse('{{ "include" }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingExpression()
	{
		$this->checkIfParserThrowsSyntaxError('{{ include }}');
	}

	public function testParsingThrowsSyntaxErrorWhenExpressionMismatch()
	{
		$this->checkIfParserThrowsSyntaxError('{{ include an_identifier }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingClosingTag()
	{
		$this->checkIfParserThrowsSyntaxError('{{ include 100');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ include 5 }}', '<?php $env->includeTemplate(5); ?>');
		$this->checkIfCompilerGivesExactResult('{{ include \'myfile.tpl\' }}', '<?php $env->includeTemplate(\'myfile.tpl\'); ?>');
		$this->checkIfCompilerGivesExactResult('{{ include 5+100 }}', '<?php $env->includeTemplate(5+100); ?>');
		$this->checkIfCompilerGivesExactResult('{{ include "my string" }}', '<?php $env->includeTemplate(\'my string\'); ?>');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(IncludeNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(IncludeNode::parse($parser));
	}

	public function checkIfParserThrowsSyntaxError($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->expectException(SyntaxError::class);
		IncludeNode::parse($parser);
	}

	public function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		IncludeNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}