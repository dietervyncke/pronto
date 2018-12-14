<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Node\IfNode;
use Pronto\Token;

class IfNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ if 10 + 10}}{{ /if }}');
		$this->checkIfParserReturnsTrue('{{if 5}}{{/if}}');
		$this->checkIfParserReturnsTrue('{{if "test" equals 5}}If body{{/if}}');
		$this->checkIfParserReturnsTrue('{{ if "test" }}If body{{ /if }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ fi }}{{ /fi }}');
		$this->checkIfParserReturnsFalse('{{ "if" }}{{ /if }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingClosingTagStart()
	{
		$this->checkIfParserThrowsSyntaxError('{{ if {{ /if }}');
	}

	public function testParsingThrowsSyntaxErrorWhenMissingClosingTagEnd()
	{
		$this->checkIfParserThrowsSyntaxError('{{ if }}{{ /if   ');
	}

	public function testParsingThrowsSyntaxErrorWhenClosingWithoutOpening()
	{
		$this->checkIfParserThrowsSyntaxError('{{ /if }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerGivesExactResult('{{ if 5 }}5{{ /if }}', '<?php if(5): ?><?php $env->write(\'5\'); ?><?php endif; ?>');
		$this->checkIfCompilerGivesExactResult('{{ if 10 +80 }}{{ /if }}', '<?php if(10+80): ?><?php endif; ?>');
		$this->checkIfCompilerGivesExactResult('{{ if "value" equals 5 }}{{ /if }}', '<?php if(\'value\'===5): ?><?php endif; ?>');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(IfNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(IfNode::parse($parser));
	}

	public function checkIfParserThrowsSyntaxError($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->expectException(SyntaxError::class);
		IfNode::parse($parser);
	}

	public function checkIfCompilerGivesExactResult($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		IfNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}