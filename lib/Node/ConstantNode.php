<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class ConstantNode extends Node
{
	private $value;

	public function __construct( $value )
	{
		$this->value = $value;
	}

	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'false' ) ||
			$parser->accept( Token::T_IDENT, 'true' ) ||
			$parser->accept( Token::T_IDENT, 'null' )
		)
		{
			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( $this->value );
	}
}