<?php

namespace Tests\Pronto;

use lib\Token;
use lib\TokenStream;
use PHPUnit\Framework\TestCase;

class TokenStreamTest extends TestCase
{
	private $tokenStream;

	public function setup()
	{
		$this->tokenStream = new TokenStream();
		$this->tokenStream->addToken(new Token('a token type', 'a token value'));
	}

	public function testAddingToken()
	{
		$this->assertCount(1, $this->tokenStream->getTokens(), 'Token count appears incorrect after adding a token');

		$this->tokenStream->addToken(new Token('a token type', 'a token value'));
		$this->assertCount(2, $this->tokenStream->getTokens(), 'Token count appears incorrect after adding a token');
	}

	public function testGetTokenAtIndexReturnsToken()
	{
		$this->assertInstanceOf(Token::class, $this->tokenStream->getToken(0), 'Getting token at index doesn\'t return an instance of Token');
	}

	public function testGetTokensReturnsArrayOfTokens()
	{
		$this->assertIsArray($this->tokenStream->getTokens(), 'Getting Tokens from TokenStream doens\'t return an array');

		foreach ($this->tokenStream->getTokens() as $token) {
			$this->assertInstanceOf(Token::class, $token);
		}
	}
}