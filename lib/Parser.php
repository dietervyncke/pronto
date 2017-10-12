<?php

namespace lib;

use lib\Node\Node;
use lib\Node\RepeatNode;
use lib\Node\RootNode;
use lib\Node\TextNode;
use lib\Node\VariableNode;

class Parser
{
	private $currentTokenIndex = 0;
	private $tokenStream;

	private $rootNode;
	private $scopeNode;

	public function __construct( TokenStream $stream )
	{
		$this->tokenStream = $stream;
		$this->rootNode = $this->scopeNode = new RootNode();
	}

	public function parse( $startPosition = 0 )
	{
		if( $startPosition >= count( $this->tokenStream->getTokens() ) )
		{
			return;
		}

		TextNode::parse( $this );

		if( $this->skip( Token::T_OPENING_TAG ) )
		{
			VariableNode::parse( $this );
			RepeatNode::parse( $this );
		}

		return $this->rootNode;
	}

	public function restartParse()
	{
		$this->parse( $this->currentTokenIndex );
	}

	public function advance()
	{
		if( $this->currentTokenIndex < count( $this->tokenStream->getTokens() ) - 1 )
		{
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

	public function insert( Node $node )
	{
		$this->getScopeNode()->addChild( $node );
	}

	public function traverseUp()
	{
		$this->setScopeNode( $this->getScopeNode()->getLastChild() );
	}

	public function traverseDown()
	{
		$this->setScopeNode( $this->getScopeNode()->getParent() );
	}

	public function getCurrentToken()
	{
		return $this->tokenStream->getToken( $this->currentTokenIndex );
	}

	public function setScopeNode( Node $node )
	{
		$this->scopeNode = $node;
	}

	public function getScopeNode()
	{
		return $this->scopeNode;
	}
}