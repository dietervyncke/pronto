<?php

namespace lib;

use lib\Node\AssignmentNode;
use lib\Node\IfNode;
use lib\Node\IncludeNode;
use lib\Node\Node;
use lib\Node\NumberNode;
use lib\Node\PrintNode;
use lib\Node\RepeatNode;
use lib\Node\RootNode;
use lib\Node\TextNode;
use lib\Node\WriteFileNode;

class Parser
{
	private $currentTokenIndex = 0;
	private $tokenStream;

	private $rootNode;
	private $scopeNode;

	public function __construct(TokenStream $stream)
	{
		$this->tokenStream = $stream;
		$this->rootNode = $this->scopeNode = new RootNode();
	}

	public function parse($startPosition = 0)
	{
		if ($startPosition >= count($this->tokenStream->getTokens())) {
			return null;
		}

		TextNode::parse( $this );
		NumberNode::parse( $this );

		if($this->skip(Token::T_OPENING_TAG)) {
			AssignmentNode::parse( $this );
			RepeatNode::parse( $this );
			WriteFileNode::parse( $this );
			IncludeNode::parse( $this );
			IfNode::parse( $this );
			PrintNode::parse( $this );
		}

		return $this->rootNode;
	}

	public function restartParse()
	{
		$this->parse($this->currentTokenIndex);
	}

	public function advance()
	{
		if ($this->currentTokenIndex < count($this->tokenStream->getTokens()) - 1) {
			$this->currentTokenIndex++;
		}
	}

	public function accept( $tokenType, $value = NULL )
	{
		return (
			$this->getCurrentToken()->getType() === $tokenType &&
			( $value ? $this->getCurrentToken()->getValue() === $value : TRUE )
		);
	}

	public function skip( $tokenType, $value = NULL )
	{
		if( $this->accept( $tokenType, $value ) )
		{
			$this->advance();
			return TRUE;
		}

		return FALSE;
	}

	public function wrap( Node $node )
	{
		$last = $this->getScopeNode()->getLastChild();
		$this->getScopeNode()->removeLastChild();

		$this->insert( $node );
		$this->traverseUp();

		$this->insert( $last );
	}

	public function traverseUp()
	{
		$this->setScopeNode($this->getScopeNode()->getLastChild());
	}

	public function traverseDown()
	{
		$this->setScopeNode($this->getScopeNode()->getParent());
	}

	public function getCurrentToken()
	{
		return $this->tokenStream->getToken($this->currentTokenIndex);
	}

	public function setScopeNode( Node $node )
	{
		$this->scopeNode = $node;
	}

	public function getScopeNode()
	{
		return $this->scopeNode;
	}

	public function setAttribute()
	{
		$last = $this->getScopeNode()->getLastChild();
		$this->getScopeNode()->removeLastChild();
		$this->getScopeNode()->setAttribute( $last );
	}

	public function insert( Node $node )
	{
		$this->getScopeNode()->addChild( $node );
	}
}