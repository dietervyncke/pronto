<?php

namespace Pronto\Node;

use Pronto\Compiler;

class RootNode extends Node
{
	public function compile(Compiler $compiler)
	{
		foreach ($this->getChildren() as $node) {
			$node->compile($compiler);
		}
	}
}