<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Parser;
use Pronto\Token;

class NumberNode extends Node
{
	private $value;

	public function __construct($value)
	{
		$this->value = $value;
	}

	public static function parse(Parser $parser)
	{
		if ($parser->accept(Token::T_NUMBER)) {
			$parser->insert(new static($parser->getCurrentToken()->getValue()));
			$parser->advance();

			return true;
		}

		return false;
	}

	public function compile(Compiler $compiler)
	{
		$compiler->writeBody($this->value);
	}
}