<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Parser;
use Pronto\Token;

class NumberNode extends Node
{
	private $beforeComma;
	private $afterComma;

	public function __construct($beforeComma, $afterComma = null)
	{
		$this->beforeComma = $beforeComma;
		$this->afterComma = $afterComma;
	}

	public static function parse(Parser $parser)
	{
		if ($parser->accept(Token::T_NUMBER)) {

			$beforeComma = $parser->getCurrentToken()->getValue();
			$afterComma = null;

			$parser->advance();

			if ($parser->accept(Token::T_OP, '.')) {

				$parser->advance();

				if ($parser->accept(Token::T_NUMBER)) {

					$afterComma = $parser->getCurrentToken()->getValue();
					$parser->advance();

				} else {

					throw new SyntaxError('Expected number after .');
				}
			}

			$parser->insert(new static($beforeComma, $afterComma));

			return true;
		}

		return false;
	}

	public function compile(Compiler $compiler)
	{
		$compiler->writeBody($this->beforeComma.($this->afterComma !== null ? '.'.$this->afterComma : ''));
	}
}