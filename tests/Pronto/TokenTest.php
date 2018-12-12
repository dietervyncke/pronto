<?php

namespace Tests\Pronto;

use Pronto\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
	public function testTokenHasCorrectValue()
	{
		$token = new Token('a token type', 'a token value');
		$this->assertEquals('a token value', $token->getValue());
	}

	public function testTokenHasCorrectType()
	{
		$token = new Token('a token type', 'a token value');
		$this->assertEquals('a token type', $token->getType());
	}

	public function testTokenNameIsUnknownWhenTypeIsUnknown()
	{
		$token = new Token('an obviously unknown type', 'a token value');
		$this->assertEquals('T_UNKNOWN', $token->getName());
	}

	public function testTokenNameIsCorrect()
	{
		$token = new Token(Token::T_TEXT, 'a token value');
		$this->assertEquals('T_TEXT', $token->getName());

		$token = new Token(Token::T_OPENING_TAG, 'a token value');
		$this->assertEquals('T_OPENING_TAG', $token->getName());

		$token = new Token(Token::T_CLOSING_TAG, 'a token value');
		$this->assertEquals('T_CLOSING_TAG', $token->getName());

		$token = new Token(Token::T_STRING, 'a token value');
		$this->assertEquals('T_STRING', $token->getName());

		$token = new Token(Token::T_IDENT, 'a token value');
		$this->assertEquals('T_IDENT', $token->getName());

		$token = new Token(Token::T_GLOBAL_VAR, 'a token value');
		$this->assertEquals('T_GLOBAL_VAR', $token->getName());

		$token = new Token(Token::T_LOCAL_VAR, 'a token value');
		$this->assertEquals('T_LOCAL_VAR', $token->getName());

		$token = new Token(Token::T_NUMBER, 'a token value');
		$this->assertEquals('T_NUMBER', $token->getName());

		$token = new Token(Token::T_SYMBOL, 'a token value');
		$this->assertEquals('T_SYMBOL', $token->getName());

		$token = new Token(Token::T_OP, 'a token value');
		$this->assertEquals('T_OP', $token->getName());

	}
}