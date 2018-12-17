<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Node\WriteFileNode;
use Pronto\Token;

class WriteFileNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ writeFile "myfile" }}{{ /writeFile }}');
		$this->checkIfParserReturnsTrue('{{ writeFile 85 }}{{ /writeFile }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ write }}{{ /write }}');
		$this->checkIfParserReturnsFalse('{{ "writeFile" }}{{ /writeFile }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingClosingTagStart()
	{
		$this->checkIfParserThrowsSyntaxError('{{ writeFile {{ /writeFile }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingClosingTagEnd()
	{
		$this->checkIfParserThrowsSyntaxError('{{ writeFile }}{{ /writeFile    ');
	}

	public function testParsingThrowsSyntaxErrorWhenClosingWithoutOpening()
	{
		$this->checkIfParserThrowsSyntaxError('{{ /writeFile }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ writeFile "string" }}my test file content{{ /writeFile }}', '<?php $env->writeFile(function() use (&$env) { ?><?php $env->write(\'my test file content\'); ?><?php },\'string\'); ?>');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(WriteFileNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(WriteFileNode::parse($parser));
	}

	public function checkIfParserThrowsSyntaxError($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->expectException(SyntaxError::class);
		WriteFileNode::parse($parser);
	}

	public function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		WriteFileNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}