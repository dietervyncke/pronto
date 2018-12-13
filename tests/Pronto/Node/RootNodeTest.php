<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\Node;
use Pronto\Node\NumberNode;
use Pronto\Node\RootNode;
use Pronto\Node\StringNode;

class RootNodeTest extends TestCase
{
	private $rootNode;

	public function setup()
	{
		$this->rootNode = new RootNode();
	}

	public function testAddingChildNode()
	{
		$this->rootNode->addChild($this->createMock(Node::class));
		$this->assertCount(1, $this->rootNode->getChildren());

		$this->rootNode->addChild($this->createMock(Node::class));
		$this->assertCount(2, $this->rootNode->getChildren());
	}

	public function testRemovingLastChild()
	{
		$this->rootNode->addChild($this->createMock(Node::class));
		$this->rootNode->addChild($this->createMock(Node::class));
		$this->rootNode->addChild($this->createMock(Node::class));
		$this->rootNode->removeLastChild();
		$this->assertCount(2, $this->rootNode->getChildren());
	}

	public function testGetParentReturnsNull()
	{
		$this->assertNull($this->rootNode->getParent());
	}

	public function testGetParentReturnsNode()
	{
		$this->rootNode->setParent($this->createMock(Node::class));
		$this->assertInstanceOf(Node::class, $this->rootNode->getParent());
	}

	public function testGetLastChildReturnsNode()
	{
		$this->rootNode->addChild($this->createMock(Node::class));
		$this->assertInstanceOf(Node::class, $this->rootNode->getLastChild());
	}

	public function testGetLastChildReturnsNull()
	{
		$this->assertNull($this->rootNode->getLastChild());
	}

	public function testIfCompilerIsCorrect()
	{
		$this->rootNode->addChild(new StringNode('string'));
		$this->rootNode->addChild(new NumberNode(5));
		$this->rootNode->addChild(new NumberNode(500));

		$compiler = new Compiler();
		$this->assertEquals('\'string\'5500', $compiler->compile($this->rootNode));
	}
}