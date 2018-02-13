<?php

namespace lib\Node;

use lib\Compiler;

class RootNode extends Node
{
	public function compile( Compiler $compiler )
	{
		foreach( $this->getChildren() as $node )
		{
			$node->compile( $compiler );
		}
	}
}