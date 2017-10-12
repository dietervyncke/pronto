<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class RepeatNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_IDENT ) )
		{
			$parser->insert( new static() );
			$parser->advance();
			$parser->traverseUp();

			// @TODO

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( 'Repeater found' );
	}
}