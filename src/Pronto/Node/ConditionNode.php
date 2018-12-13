<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Parser;

class ConditionNode extends Node
{
	public static function parse(Parser $parser, $strict = false)
	{
		if (ExpressionNode::parse($parser) || LogicalOperatorNode::parse($parser)) {

			if (!$parser->getScopeNode() instanceof self) {
				$parser->wrap(new static());
			}

			if (ExpressionNode::parse($parser) || LogicalOperatorNode::parse($parser)) {
				self::parse($parser);

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