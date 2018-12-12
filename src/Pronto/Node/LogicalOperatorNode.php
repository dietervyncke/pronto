<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Parser;
use Pronto\Token;

class LogicalOperatorNode extends Node
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