<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class OperatorNode extends Node
{
	private $sign;

	public function __construct( $sign )
	{
		$this->sign = $sign;
	}

	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_OP ) )
		{
			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		if( $this->sign === '-' )
		{
			$compiler->writeBody( ' - ' );
		}
		elseif( $this->sign === '*' )
		{
			$compiler->writeBody( ' * ' );
		}
		elseif( $this->sign === '+' )
		{
			$compiler->writeBody( ' . ' );
		}
		else
		{
			$compiler->writeBody( ' + ' );
		}
	}
}