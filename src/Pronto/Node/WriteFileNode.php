<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Parser;
use Pronto\Token;

class WriteFileNode extends Node
{
	public static function parse( Parser $parser )
	{
		if ($parser->accept(Token::T_IDENT, 'writeFile')) {

			$parser->insert(new static());
			$parser->traverseUp();
			$parser->advance();

			if (! ExpressionNode::parse($parser)) {
				throw new SyntaxError('Expected expression');
			}

			$parser->setAttribute();

			$parser->expect(Token::T_CLOSING_TAG);
			$parser->advance();
			$parser->restartParse();

			return true;
		}

		if ($parser->skip(Token::T_IDENT, '/writeFile')) {

			if (! $parser->getScopeNode() instanceof self) {
				throw new SyntaxError('Wrongly placed closing of node write_file');
			}

			$parser->expect(Token::T_CLOSING_TAG);
			$parser->advance();
			$parser->traverseDown();
			$parser->restartParse();
		}

		return false;
	}

	public function compile(Compiler $compiler)
	{
		$compiler->writeBody('<?php $env->writeFile(function() use (&$env) { ?>');

		foreach ($this->getChildren() as $child) {
			$child->compile($compiler);
		}

		$compiler->writeBody('<?php }');

		if (count($this->getAttributes())) {
			$compiler->writeBody(',');
		}

		foreach ($this->getAttributes() as $a) {
			$subcompiler = new Compiler();
			$compiler->writeBody($subcompiler->compile($a));
		}

		$compiler->writeBody('); ?>');
	}
}