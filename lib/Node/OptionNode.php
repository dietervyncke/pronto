<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class OptionNode extends Node
{
	private $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function compile( Compiler $compiler )
	{
	}
}