<?php

namespace lib;

class TokenStream
{
	private $tokens = [];

	public function addToken(Token $token)
	{
		$this->tokens[] = $token;
	}

	public function getToken($i)
	{
		return $this->tokens[$i];
	}

	public function getTokens()
	{
		return $this->tokens;
	}
}