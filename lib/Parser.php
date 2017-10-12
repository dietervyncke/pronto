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

		if( $this->skip( Token::T_OPENING_TAG ) )
		{
			VariableNode::parse( $this );
			RepeatNode::parse( $this );

			if( $this->skip( Token::T_CLOSING_TAG ) )
			{
				$this->parse( $this->currentTokenIndex );
			}
		}
		else
		{
			TextNode::parse( $this );
			$this->parse( $this->currentTokenIndex );
		}

		return $this->rootNode;
	}

	public function advance()
	{
		$this->currentTokenIndex++;
	}

	public function accept( $tokenType )
	{
		return $this->getCurrentToken()->getType() === $tokenType;
	}

	public function skip( $tokenType )
	{
		if( $this->tokenStream->getToken( $this->currentTokenIndex )->getType() === $tokenType )
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