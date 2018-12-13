<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\OperatorNode;
use Pronto\Token;

class OperatorNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ + }}');
		$this->checkIfParserReturnsTrue('{{ - }}');
		$this->checkIfParserReturnsTrue('{{ / }}');
		$this->checkIfParserReturnsTrue('{{ * }}');
		$this->checkIfParserReturnsTrue('{{ % }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ @ }}');
		$this->checkIfParserReturnsFalse('{{ lol }}');
		$this->checkIfParserReturnsFalse('{{ , }}');
		$this->checkIfParserReturnsFalse('{{ 54 - 1 }}');
		$this->checkIfParserReturnsFalse('{{ /repeat }}');
		$this->checkIfParserReturnsFalse('{{ /if }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerIsCorrect('{{ 5 +2 }}', '<?php $env->write(5+2); ?>');
		$this->checkIfCompilerIsCorrect('{{6+9}}', '<?php $env->write(6+9); ?>');
		$this->checkIfCompilerIsCorrect('{{1-6}}', '<?php $env->write(1-6); ?>');
		$this->checkIfCompilerIsCorrect('{{2%3}}', '<?php $env->write(2%3); ?>');
		$this->checkIfCompilerIsCorrect('{{ 10*5 - 8 }}', '<?php $env->write(10*5-8); ?>');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(OperatorNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(OperatorNode::parse($parser));
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