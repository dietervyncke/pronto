<?php

namespace Pronto;

use Pronto\Node\Node;

class Compiler
{
	private $head = '';
	private $body = '';

	public function compile( Node $rootNode )
	{
		$rootNode->compile( $this );

		return $this->head . $this->body;
	}

	public function writeHead( $string )
	{
		$this->head .= $string;
	}

	public function writeBody( $string )
	{
		$this->body .= $string;
	}
}