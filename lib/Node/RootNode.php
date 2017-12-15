<?php

namespace lib\Node;

use lib\Compiler;

class RootNode extends Node
{
	public function compile( Compiler $compiler )
	{
		$compiler->writeHead( '<?php require_once \'lib/Environment.php\'; ?>' );
		$compiler->writeHead( '<?php $env = \lib\Environment::get(); ?>' );

		foreach( $this->getChildren() as $node )
		{
			$node->compile( $compiler );
		}

		$compiler->writeBody( '<?php return $env->getOutput(); ?>' );
	}
}