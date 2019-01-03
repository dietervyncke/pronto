<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Node\FileNode;
use Pronto\Token;

class FileNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ file "myfile" }}test{{ /file }}');
		$this->checkIfParserReturnsTrue('{{ file 85 }}{{ /file }}');
		$this->checkIfParserReturnsTrue('{{ file "test.pronto" prepend }}{{ /file }}');
		$this->checkIfParserReturnsTrue('{{ file "test.pronto" append }}{{ /file }}');
		$this->checkIfParserReturnsTrue('{{ file 5000 append }}{{ /file }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ write }}{{ /write }}');
		$this->checkIfParserReturnsFalse('{{ "writeFile" }}{{ /writeFile }}');
		$this->checkIfParserReturnsFalse('{{ append }}{{ /file }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingExpression()
	{
		$this->checkIfParserThrowsSyntaxError('{{ file }}{{ /file }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingClosingTagEnd()
	{
		$this->checkIfParserThrowsSyntaxError('{{ file 50 append }}{{ /file         ');
	}

	public function testParsingThrowsSyntaxErrorWhenClosingWithoutOpening()
	{
		$this->checkIfParserThrowsSyntaxError('{{ /file }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ file "string" }}my test file content{{ /file }}', '<?php $env->writeFile(function() use (&$env) { ?><?php $env->write(\'my test file content\'); ?><?php },\'string\'); ?>');
		$this->checkIfCompilerGivesExactResult('{{ file "string" append }}my test file content{{ /file }}', '<?php $env->appendFile(function() use (&$env) { ?><?php $env->write(\'my test file content\'); ?><?php },\'string\'); ?>');
		$this->checkIfCompilerGivesExactResult('{{ file "string" prepend }}my test file content{{ /file }}', '<?php $env->prependFile(function() use (&$env) { ?><?php $env->write(\'my test file content\'); ?><?php },\'string\'); ?>');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(FileNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(FileNode::parse($parser));
	}

	public function checkIfParserThrowsSyntaxError($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->expectException(SyntaxError::class);
		FileNode::parse($parser);
	}

	public function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		FileNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}