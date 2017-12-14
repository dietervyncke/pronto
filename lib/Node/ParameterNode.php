<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class ParameterNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( ExpressionNode::parse( $parser ) )
		{
			if( !$parser->getScopeNode() instanceof self )
			{
				$parser->wrap( new static() );
			}

			if( $parser->skip( Token::T_SYMBOL, ',' ) )
			{
				self::parse( $parser );
			}
			else
			{
				$parser->traverseDown();
			}

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( '[' );

		foreach( $this->getChildren() as $child )
		{
			$child->compile( $compiler );

			if( ! ( end( $this->getChildren() ) === $child ) )
			{
				$compiler->writeBody( ',' );
			}
		}

		$compiler->writeBody( ']' );
	}
}