<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Parser;
use Pronto\Token;

class IfNode extends Node
{
	public static function parse(Parser $parser)
	{
		if ($parser->accept(Token::T_IDENT, 'if')) {

			$parser->insert(new static());
			$parser->traverseUp();
			$parser->advance();

			if (! ConditionNode::parse($parser)) {
				throw new SyntaxError('Missing or mismatched condition in if node');
			}

			$parser->setAttribute();

			$parser->expect(Token::T_CLOSING_TAG);
			$parser->advance();
			$parser->restartParse();

			return true;
		}

		if ($parser->skip(Token::T_IDENT, '/if')) {

			if (! $parser->getScopeNode() instanceof self) {
				throw new SyntaxError('Wrongly placed closing of node if');
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
		$compiler->writeBody('<?php if(');

		foreach ($this->getAttributes() as $a) {
			$a->compile($compiler);
		}

		$compiler->writeBody('): ?>');

		foreach ($this->getChildren() as $child) {
			$child->compile($compiler);
		}

		$compiler->writeBody( '<?php endif; ?>' );
	}
}