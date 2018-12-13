<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\StringNode;
use Pronto\Node\TextNode;
use Pronto\Token;

class StringNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ "hi" }}');
		$this->checkIfParserReturnsTrue('{{ \'hi there\' }}');
		$this->checkIfParserReturnsTrue('{{ \'hi 5 \' }}');
		$this->checkIfParserReturnsTrue('{{ \'hi {{}} \' }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ 5 }}');
		$this->checkIfParserReturnsFalse('{{ \'hi there" }}');
		$this->checkIfParserReturnsFalse('{{ "hi there\' }}');
		$this->checkIfParserReturnsFalse('{{ {{ hi there\' }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ "hi" }}', '\'hi\'');
		$this->checkIfCompilerGivesExactResult('{{ \'hi there\' }}', '\'hi there\'');
		$this->checkIfCompilerGivesExactResult('{{ \'5\' }}', '\'5\'');
		$this->checkIfCompilerGivesExactResult('{{ \'{{\' }}', '\'{{\'');
	}

	private function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(StringNode::parse($parser));
	}

	private function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(StringNode::parse($parser));
	}

	private function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		StringNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}