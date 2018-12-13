<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Node\RepeatNode;
use Pronto\Token;

class RepeatNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ repeat }}{{ /repeat }}');
		$this->checkIfParserReturnsTrue('{{repeat}}{{/repeat}}');
		$this->checkIfParserReturnsTrue('{{repeat}}     {{/repeat}}');
		$this->checkIfParserReturnsTrue('{{ repeat \'repeat name\' }}{{ /repeat }}');
		$this->checkIfParserReturnsTrue('{{ repeat 10 }}{{ /repeat }}');
		$this->checkIfParserReturnsTrue('{{ repeat ?-testje }}repeat content{{ /repeat }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ repea }}{{ /repeat }}');
		$this->checkIfParserReturnsFalse('{{ "just a string" }}{{ /repeat }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingClosingTagStart()
	{
		$this->checkIfParserThrowsSyntaxError('{{ repeat {{ /repeat }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingClosingTagEnd()
	{
		$this->checkIfParserThrowsSyntaxError('{{ repeat }}{{ /repeat    ');
	}

	public function testParsingThrowsSyntaxErrorWhenClosingWithoutOpening()
	{
		$this->checkIfParserThrowsSyntaxError('{{ /repeat }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ repeat }}5{{ /repeat }}', '<?php $env->repeat(function() use (&$env) { ?><?php $env->write(\'5\'); ?><?php }); ?>');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(RepeatNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(RepeatNode::parse($parser));
	}

	public function checkIfParserThrowsSyntaxError($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->expectException(SyntaxError::class);
		RepeatNode::parse($parser);
	}

	public function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		RepeatNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}