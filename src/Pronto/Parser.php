<?php

namespace Pronto;

use Pronto\Exception\SyntaxError;
use Pronto\Node\AssignmentNode;
use Pronto\Node\IfNode;
use Pronto\Node\IncludeNode;
use Pronto\Node\Node;
use Pronto\Node\PrintNode;
use Pronto\Node\RepeatNode;
use Pronto\Node\RootNode;
use Pronto\Node\TextNode;
use Pronto\Node\WriteFileNode;

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

		TextNode::parse($this);

		if ($this->skip(Token::T_OPENING_TAG)) {

			AssignmentNode::parse($this);
			RepeatNode::parse($this);
			IncludeNode::parse($this);
			PrintNode::parse($this);
			WriteFileNode::parse($this);
			IfNode::parse($this);
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

	public function accept($tokenType, $value = null)
	{
		return (
			$this->getCurrentToken()->getType() === $tokenType &&
			( $value ? $this->getCurrentToken()->getValue() === $value : true )
		);
	}

	public function expect($tokenType, $value = null): bool
	{
		if (!$this->accept($tokenType, $value)) {
			throw new SyntaxError('Expected '.Token::getNameByType($tokenType).' got '.$this->getCurrentToken()->getName());
		}

		return true;
	}

	public function skip($tokenType, $value = null)
	{
		if($this->accept($tokenType, $value)) {
			$this->advance();
			return true;
		}

		return false;
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

	public function insert(Node $node)
	{
		$this->getScopeNode()->addChild($node);
	}
}