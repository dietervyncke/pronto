<?php

namespace lib\Node;

use lib\Compiler;

abstract class Node
{
	private $parent = NULL;
	private $children = [];
	private $attributes = [];

	public function addChild( Node $node )
	{
		$this->children[] = $node;
		$node->setParent( $this );
	}

	public function setAttribute( Node $node )
	{
		$this->attributes[] = $node;
	}

	public function getAttribute( $i )
	{
		return isset( $this->attributes[ $i ] ) ? $this->attributes[ $i ] : null;
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	public function getChildren()
	{
		return $this->children;
	}

	public function getLastChild()
	{
		return end( $this->children );
	}

	public function removeLastChild()
	{
		array_pop( $this->children );
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function setParent( Node $parent ){

		$this->parent = $parent;
	}

	public abstract function compile( Compiler $compiler );
}