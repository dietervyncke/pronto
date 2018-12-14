<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Parser;
use Pronto\Token;

class ConstantNode extends Node
{
	private $name;

	const CONSTANTS = [
		'ZERO' => '0',
		'ONE' => '1',
		'TRUE' => 'true',
		'FALSE' => 'false',
		'NULL' => 'null',
		'PI' => '3.1415927',
		'E' => '2.71828',
	];

	public function __construct($name)
	{
		$this->name = $name;
	}

	public static function parse(Parser $parser)
	{
		if ($parser->accept(Token::T_IDENT)) {
			$currentTokenValue = $parser->getCurrentToken()->getValue();

			if (array_key_exists($currentTokenValue, self::CONSTANTS)) {

				$parser->insert(new static($currentTokenValue));
				$parser->advance();

				return true;
			}
		}

		return false;
	}

	public function compile(Compiler $compiler)
	{
		$compiler->writeBody(self::CONSTANTS[$this->name]);
	}
}