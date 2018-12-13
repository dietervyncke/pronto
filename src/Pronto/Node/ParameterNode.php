<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Parser;
use Pronto\Token;

class ParameterNode extends Node
{
	public static function parse(Parser $parser, $strict = false)
	{
		if (ExpressionNode::parse($parser)) {

			if (!$parser->getScopeNode() instanceof self) {
				$parser->wrap(new static());
			}

			if ($parser->skip(Token::T_SYMBOL, ',')){
				self::parse($parser, true);
			} else {
				$parser->traverseDown();
			}

			return true;
		}

		if ($strict) {
			throw new SyntaxError('Expected expression');
		}

		return false;
	}

	public function compile(Compiler $compiler)
	{
		$compiler->writeBody('[');

		foreach ($this->getChildren() as $child) {
			$child->compile($compiler);
			$compiler->writeBody(',');
		}

		$compiler->writeBody(']');
	}
}