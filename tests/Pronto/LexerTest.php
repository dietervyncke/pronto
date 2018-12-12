<?php

namespace Tests\Pronto;

use Pronto\Lexer;
use Pronto\Token;
use Pronto\TokenStream;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
	private $lexer;

	public function setup()
	{
		$this->lexer = new Lexer();
	}

	public function testTokenizeShouldReturnInstanceOfTokenStream()
	{
		$this->tokenizeShouldReturnInstanceOfTokenStream('');
		$this->tokenizeShouldReturnInstanceOfTokenStream(' ');
		$this->tokenizeShouldReturnInstanceOfTokenStream('some dummy text');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{');
		$this->tokenizeShouldReturnInstanceOfTokenStream('}}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ "dummy" }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ ?=global_var }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ ?-private_var }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ var ?-private_var is 10 }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ repeat }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ repeat( "Want to repeat something?" ) }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ repeat( "Want to repeat something?" ) }} hi there {{ /repeat }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ /repeat }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ if ?=var is false }}');
		$this->tokenizeShouldReturnInstanceOfTokenStream('{{ /if }}');
	}

	public function testTokenValue()
	{
		$this->tokenValueTest('{{', '');
		$this->tokenValueTest('}}', '}}');
		$this->tokenValueTest('{{}}', '');
		$this->tokenValueTest('{{ 5 }}', 5, 1);
		$this->tokenValueTest('{{ ?=global_var }}', 'global_var', 1);
		$this->tokenValueTest('{{ ?-private_var }}', 'private_var', 1);
		$this->tokenValueTest('{{ ?-global_var( "option 1", "option 2" ) }}', 'option 1', 3);
		$this->tokenValueTest('{{ ?-global_var( "option 1" ) }}', '(', 2);
		$this->tokenValueTest('3', 3);
		$this->tokenValueTest('Dummy text', 'Dummy text');
		$this->tokenValueTest('{{ Dummy text }}', 'Dummy', 1);
		$this->tokenValueTest('5 + Dummy text', '5 + Dummy text');
		$this->tokenValueTest('{{ 5 + 5 }}', '+', 2);
		$this->tokenValueTest('5.45', 5.45);
		$this->tokenValueTest('{{ if true }}', 'if', 1);
	}

	public function testTokenValueTypes()
	{
		$this->tokenTypesShouldMatch('', []);
		$this->tokenTypesShouldMatch('{{', [Token::T_OPENING_TAG]);
		$this->tokenTypesShouldMatch('{{}}', [Token::T_OPENING_TAG, Token::T_CLOSING_TAG]);
		$this->tokenTypesShouldMatch('{{}}', [Token::T_OPENING_TAG, Token::T_CLOSING_TAG]);
		$this->tokenTypesShouldMatch('{{5 }}', [Token::T_OPENING_TAG, Token::T_NUMBER, Token::T_CLOSING_TAG]);
		$this->tokenTypesShouldMatch('{{   5 }}', [Token::T_OPENING_TAG, Token::T_NUMBER, Token::T_CLOSING_TAG]);

		$this->tokenTypesShouldMatch('Dummy text {{ ?=global_var }}', [
			Token::T_TEXT,
			Token::T_OPENING_TAG, Token::T_GLOBAL_VAR, Token::T_CLOSING_TAG
		]);

		$this->tokenTypesShouldMatch('5 + 5 {{ ?=private_var }} ?=global_var', [
			Token::T_TEXT,
			Token::T_OPENING_TAG, Token::T_GLOBAL_VAR, Token::T_CLOSING_TAG,
			Token::T_TEXT
		]);

		$this->tokenTypesShouldMatch('{{ + }}', [
			Token::T_OPENING_TAG, Token::T_OP ,Token::T_CLOSING_TAG
		]);

		$this->tokenTypesShouldMatch('{{ if }}{{ /if }}', [
			Token::T_OPENING_TAG, Token::T_IDENT ,Token::T_CLOSING_TAG,
			Token::T_OPENING_TAG, Token::T_IDENT ,Token::T_CLOSING_TAG
		]);

		$this->tokenTypesShouldMatch('{{ if 5 equals 10 }} Correct {{ /if }}', [
			Token::T_OPENING_TAG, Token::T_IDENT, Token::T_NUMBER, Token::T_IDENT, Token::T_NUMBER, Token::T_CLOSING_TAG,
			Token::T_TEXT,
			Token::T_OPENING_TAG, Token::T_IDENT, Token::T_CLOSING_TAG
		]);

		$this->tokenTypesShouldMatch('{{ if 5 + 5 equals 10 }} Correct {{ /if }}', [
			Token::T_OPENING_TAG, Token::T_IDENT, Token::T_NUMBER, Token::T_OP, Token::T_NUMBER, Token::T_IDENT, Token::T_NUMBER, Token::T_CLOSING_TAG,
			Token::T_TEXT,
			Token::T_OPENING_TAG, Token::T_IDENT, Token::T_CLOSING_TAG
		]);

		$this->tokenTypesShouldMatch('{{ var ?=price is 10 }}', [
			Token::T_OPENING_TAG, Token::T_IDENT, Token::T_GLOBAL_VAR, Token::T_IDENT, Token::T_NUMBER, Token::T_CLOSING_TAG
		]);

		$this->tokenTypesShouldMatch('{{ repeat( "Repeat something?" ) }} Yes repeat all the way {{ /repeat }}', [
			Token::T_OPENING_TAG, Token::T_IDENT, Token::T_SYMBOL, Token::T_STRING, Token::T_SYMBOL, Token::T_CLOSING_TAG,
			Token::T_TEXT,
			Token::T_OPENING_TAG, Token::T_IDENT, Token::T_CLOSING_TAG
		]);

		$this->tokenTypesShouldMatch('{{ write_file( "test.php" ) }}{{ /write_file }}', [
			Token::T_OPENING_TAG, Token::T_IDENT, Token::T_SYMBOL, Token::T_STRING, Token::T_SYMBOL ,Token::T_CLOSING_TAG,
			Token::T_OPENING_TAG, Token::T_IDENT, Token::T_CLOSING_TAG
		]);
	}

	private function tokenizeShouldReturnInstanceOfTokenStream($input)
	{
		$this->assertInstanceOf(TokenStream::class, $this->lexer->tokenize($input));
	}

	private function tokenValueTest($input, $value, $position = 0)
	{
		$stream = $this->lexer->tokenize($input);
		$this->assertEquals($value, $stream->getToken($position)->getValue(), 'Value of token does not match '.$value);
	}

	private function tokenTypesShouldMatch($input, $tokens)
	{
		$stream = $this->lexer->tokenize($input);
		$this->assertCount(count($tokens), $stream->getTokens(), 'Amount of tokens does not match expected amount');

		foreach ($tokens as $k => $type) {
			$this->assertEquals($type, $stream->getToken($k)->getType(), 'Type of token does not match '.$type);
		}
	}
}