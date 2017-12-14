<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;

class ExpressionNode extends Node
{
	public static function parse( Parser $parser )
	{
		if(	GlobalVariableNode::parse( $parser ) || StringNode::parse( $parser ) )
		{
			if( !$parser->getScopeNode() instanceof self )
			{
				$parser->wrap( new static() );
			}

			if( OperatorNode::parse( $parser ) )
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
		foreach( $this->getChildren() as $child )
		{
			$child->compile( $compiler );
		}
	}
}