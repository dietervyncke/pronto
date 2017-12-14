<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;

class ConditionNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( ExpressionNode::parse( $parser ) || OperatorNode::parse( $parser ) ) {

			if( !$parser->getScopeNode() instanceof self )
			{
				$parser->wrap( new static() );
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