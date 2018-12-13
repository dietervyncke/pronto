<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\NumberNode;
use Pronto\Token;

class NumberNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ 10 }}');
		$this->checkIfParserReturnsTrue('{{ 50.004 }}');
		$this->checkIfParserReturnsTrue('{{ 0.4545 }}');
		$this->checkIfParserReturnsTrue('{{ .75 }}');
		$this->checkIfParserReturnsTrue('{{ 4545454241224 }}');
		$this->checkIfParserReturnsTrue('{{ .1 }}');
		$this->checkIfParserReturnsTrue('{{ 0 }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ ,75 }}');
		$this->checkIfParserReturnsFalse('{{ test }}');
		$this->checkIfParserReturnsFalse('{{ "50" }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerIsCorrect('{{ 10 }}', '<?php $env->write(10); ?>');
		$this->checkIfCompilerIsCorrect('{{ 1.00 }}', '<?php $env->write(1.00); ?>');
		$this->checkIfCompilerIsCorrect('{{ .75 }}', '<?php $env->write(.75); ?>');
		$this->checkIfCompilerIsCorrect('{{ 5000.000 }}', '<?php $env->write(5000.000); ?>');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(NumberNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(NumberNode::parse($parser));
	}

	public function checkIfCompilerIsCorrect($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);

		$compiler = new Compiler();
		$this->assertEquals($compiled, $compiler->compile($parser->parse()));
	}
}