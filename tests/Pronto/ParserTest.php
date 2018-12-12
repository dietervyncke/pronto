<?php

namespace Tests\Pronto;

use Pronto\Node\RootNode;
use Pronto\Parser;
use Pronto\Token;
use Pronto\TokenStream;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
	public function testParsingReturnsNull()
	{
		$parser = new Parser(new TokenStream());

		$this->assertNull($parser->parse());
	}

	public function testParsingReturnsRootNode()
	{
		$tokenStream = new TokenStream();
		$tokenStream->addToken(new Token('a token type', 'a token value'));

		$parser = new Parser($tokenStream);

		$this->assertInstanceOf(RootNode::class, $parser->parse());
	}

	public function testGetCurrentToken()
	{
		$tokenStream = new TokenStream();
		$tokenStream->addToken(new Token('a token type', 'a token value'));

		$parser = new Parser($tokenStream);

		$this->assertInstanceOf(Token::class, $parser->getCurrentToken());
		$this->assertEquals('a token value', $parser->getCurrentToken()->getValue());
	}
}