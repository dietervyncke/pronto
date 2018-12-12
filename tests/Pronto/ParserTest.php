<?php

namespace Tests\Pronto;

use lib\Node\RootNode;
use lib\Parser;
use lib\Token;
use lib\TokenStream;
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
}