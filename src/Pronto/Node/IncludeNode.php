<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Parser;
use Pronto\Token;

class IncludeNode extends Node
{
	public static function parse(Parser $parser)
	{
		if ($parser->accept(Token::T_IDENT, 'include')) {

			$parser->insert(new static($parser->getCurrentToken()->getValue()));
			$parser->traverseUp();
			$parser->advance();

			if (! ExpressionNode::parse($parser)) {
				throw new SyntaxError('Expected expression');
			}

			$parser->setAttribute();

			$parser->expect( Token::T_CLOSING_TAG );
			$parser->traverseDown();
			$parser->restartParse();

			return true;
		}

		return false;
	}

	public function compile(Compiler $compiler)
	{
		$compiler->writeBody('<?php $env->includeTemplate(');

		foreach ($this->getAttributes() as $a) {
			$subcompiler = new Compiler();
			$compiler->writeBody($subcompiler->compile($a));
		}

		$compiler->writeBody('); ?>');
	}
}