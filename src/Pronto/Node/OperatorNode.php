<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Parser;
use Pronto\Token;

class OperatorNode extends Node
{
	private $sign;

	public function __construct($sign)
	{
		$this->sign = $sign;
	}

	public static function parse(Parser $parser)
	{
		if ($parser->accept(Token::T_OP)) {
			$parser->insert(new static($parser->getCurrentToken()->getValue()));
			$parser->advance();

			return true;
		}

		return false;
	}

	public function compile(Compiler $compiler)
	{
		$compiler->writeBody($this->sign);
	}
}