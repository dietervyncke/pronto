<?php

namespace lib\Node;

use lib\Compiler;

class RootNode extends Node
{
	public function compile( Compiler $compiler )
	{
		$compiler->writeHead('<?php $output = \'\'; ?>' );

		foreach( $this->getChildren() as $node )
		{
			$node->compile( $compiler );
		}

		$compiler->writeBody( '<?php return $output; ?>' );
	}
}