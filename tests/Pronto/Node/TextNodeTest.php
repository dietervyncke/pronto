<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\TextNode;

class TextNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue(' ');
		$this->checkIfParserReturnsTrue('     ');
		$this->checkIfParserReturnsTrue('dummy text');
		$this->checkIfParserReturnsTrue('dummy text,, some "more"');
		$this->checkIfParserReturnsTrue('\' more text');
		$this->checkIfParserReturnsTrue(5);
		$this->checkIfParserReturnsTrue('5 }}');
		$this->checkIfParserReturnsTrue('}}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{');
		$this->checkIfParserReturnsFalse('{{ 5 }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('Dummy text', '<?php $env->write(\'Dummy text\'); ?>');
		$this->checkIfCompilerGivesExactResult('Dummy  text  ', '<?php $env->write(\'Dummy  text  \'); ?>');
		$this->checkIfCompilerGivesExactResult('5 }} + test', '<?php $env->write(\'5 }} + test\'); ?>');
	}

	private function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);

		$this->assertTrue(TextNode::parse($parser));
	}

	private function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);

		$this->assertFalse(TextNode::parse($parser));
	}

	private function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);

		$compiler = new Compiler();
		$this->assertEquals($compiled, $compiler->compile($parser->parse()));
	}
}