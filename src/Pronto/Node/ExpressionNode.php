<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Parser;

class ExpressionNode extends Node
{
	public static function parse(Parser $parser, $strict = false)
	{
		if (GlobalVariableNode::parse($parser) ||
			StringNode::parse($parser) ||
			NumberNode::parse($parser) ||
			LocalVariableNode::parse($parser))
		{
			if (!$parser->getScopeNode() instanceof self) {
				$parser->wrap(new static());
			}

			if (OperatorNode::parse($parser)) {
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
		foreach ($this->getChildren() as $child) {
			$child->compile($compiler);
		}
	}
}