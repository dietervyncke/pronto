<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Pronto\Node\ConditionNode;
use Pronto\Token;

class ConditionNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ 5+6 equals 10 }}');
		$this->checkIfParserReturnsTrue('{{ 5 equals 10 }}');
		$this->checkIfParserReturnsTrue('{{ 5 }}' );
		$this->checkIfParserReturnsTrue('{{ equals }}' );
		$this->checkIfParserReturnsTrue('{{ 5+6+4 equals 50 }}dummy text{{ /if }}');
		$this->checkIfParserReturnsTrue('{{ 5 equals 5 }}');
	}

	private function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);
		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(ConditionNode::parse($parser));
	}
}