<?php

namespace lib\Node;

use lib\Compiler;

abstract class Node
{
	public $children = [];

	public function addChild( $node )
	{
		$this->children[] = $node;
	}

	public function getChildren()
	{
		return $this->children;
	}

	public function getLastChild()
	{
		return end( $this->children );
	}

	public abstract function compile( Compiler $compiler );
}