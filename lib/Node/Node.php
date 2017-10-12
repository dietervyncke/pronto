<?php

namespace lib\Node;

use lib\Compiler;

abstract class Node
{
	private $parent = NULL;
	private $children = [];

	public function addChild( Node $node )
	{
		$this->children[] = $node;
		$node->setParent( $this );
	}

	public function getChildren()
	{
		return $this->children;
	}

	public function getLastChild()
	{
		return end( $this->children );
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function setParent( Node $parent )
	{
		$this->parent = $parent;
	}

	public abstract function compile( Compiler $compiler );
}