<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Parser;
use Pronto\Token;

class RepeatNode extends Node
{
	public static function parse(Parser $parser)
	{
		if ($parser->accept(Token::T_IDENT, 'repeat')) {

			$parser->insert(new static());
			$parser->traverseUp();
			$parser->advance();

			if (ExpressionNode::parse($parser)) {
				$parser->setAttribute();
			}

			$parser->expect(Token::T_CLOSING_TAG);
			$parser->advance();
			$parser->restartParse();

			return true;
		}

		if ($parser->skip(Token::T_IDENT, '/repeat')) {

			if (! $parser->getScopeNode() instanceof self) {
				throw new SyntaxError('Wrongly placed closing of node repeat');
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
		$compiler->writeBody( '<?php $env->repeat(function() use (&$env) { ?>' );

		foreach ($this->getChildren() as $child) {
			$child->compile($compiler);
		}

		$compiler->writeBody( '<?php }' );

		if (count($this->getAttributes())) {
			$compiler->writeBody( ',' );
		}

		foreach ($this->getAttributes() as $a) {
			$subcompiler = new Compiler();
			$compiler->writeBody( $subcompiler->compile( $a ) );
		}

		$compiler->writeBody( '); ?>' );
	}
}