<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Parser;
use Pronto\Token;

class LocalVariableNode extends Node
{
	private $name;

	public function __construct( $name )
	{
		$this->name = $name;
	}

	public static function parse( Parser $parser )
	{
		if ($parser->accept(Token::T_LOCAL_VAR)) {

			$parser->insert(new static($parser->getCurrentToken()->getValue()));
			$parser->advance();

			if ($parser->skip(Token::T_SYMBOL, '(')) {

				$parser->traverseUp();

				if (ParameterNode::parse($parser)) {
					$parser->setAttribute();
				}

				$parser->skip(Token::T_SYMBOL, ')');
				$parser->traverseDown();
			}

			return true;
		}

		return false;
	}

	public function getName()
	{
		return $this->name;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( '$env->getLocalVariable(\'' . $this->name . '\'' );

		if (count($this->getAttributes())) {
			$compiler->writeBody(', ');
		}

		foreach ($this->getAttributes() as $a) {
			$subcompiler = new Compiler();
			$compiler->writeBody($subcompiler->compile($a));
		}

		$compiler->writeBody(')');
	}
}