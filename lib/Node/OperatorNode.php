<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class OperatorNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'equals' ) )
		{
			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( ' === ' );
	}
}