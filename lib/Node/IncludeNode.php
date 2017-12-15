<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class IncludeNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'include' ) )
		{
			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->traverseUp();
			$parser->advance();

			if( ExpressionNode::parse( $parser ) )
			{
				$parser->setAttribute();
			}

			$parser->skip( Token::T_CLOSING_TAG );
			$parser->traverseDown();
			$parser->restartParse();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( '<?php $env->includeTemplate( ' );

		foreach ( $this->getAttributes() as $a )
		{
			$subcompiler = new Compiler();
			$compiler->writeBody( $subcompiler->compile( $a ) );
		}

		$compiler->writeBody( '); ?>' );
	}
}