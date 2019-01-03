<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Exception\SyntaxError;
use Pronto\Parser;
use Pronto\Token;

class FileNode extends Node
{
	const TYPE_DEFAULT = 0;
	const TYPE_APPEND = 1;
	const TYPE_PREPEND = 2;

	private $type = 0;

	public function setType(int $type)
	{
		$this->type = $type;
	}

	public function getType(): int
	{
		return $this->type;
	}

	public static function parse(Parser $parser)
	{
		if ($parser->accept(Token::T_IDENT, 'file')) {

			$node = new static();

			$parser->insert($node);
			$parser->traverseUp();
			$parser->advance();

			if (! ExpressionNode::parse($parser)) {
				throw new SyntaxError('Expected expression');
			}

			$parser->setAttribute();

			if ($parser->accept(Token::T_IDENT, 'append')) {
				$node->setType(self::TYPE_APPEND);
				$parser->advance();
			} else if ($parser->accept(Token::T_IDENT, 'prepend')) {
				$node->setType(self::TYPE_PREPEND);
				$parser->advance();
			}

			$parser->expect(Token::T_CLOSING_TAG);
			$parser->advance();
			$parser->restartParse();

			return true;
		}

		if ($parser->skip(Token::T_IDENT, '/file')) {

			if (! $parser->getScopeNode() instanceof self) {
				throw new SyntaxError('Wrongly placed closing of node file');
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
		$fileFunctionName = 'writeFile';

		if ($this->getType() === self::TYPE_APPEND) {
			$fileFunctionName = 'appendFile';
		} else if ($this->getType() === self::TYPE_PREPEND) {
			$fileFunctionName = 'prependFile';
		}

		$compiler->writeBody('<?php $env->'.$fileFunctionName.'(function() use (&$env) { ?>');

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