<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Parser;

class ConditionNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( ExpressionNode::parse( $parser ) || LogicalOperatorNode::parse( $parser ) )
		{
			if( !$parser->getScopeNode() instanceof self )
			{
				$parser->wrap( new static() );
			}

			if( ExpressionNode::parse( $parser ) || LogicalOperatorNode::parse( $parser ) )
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