<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class IfNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'if' ) )
		{
			$parser->insert( new static() );
			$parser->traverseUp();
			$parser->advance();

			if( ConditionNode::parse( $parser ) )
			{
				$parser->setAttribute();
			}

			if( $parser->skip( Token::T_CLOSING_TAG ) )
			{
				$parser->restartParse();
			}

			$parser->skip( Token::T_IDENT, '/if' );
			$parser->skip( Token::T_CLOSING_TAG );
			$parser->traverseDown();
			$parser->restartParse();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( '<?php if( ' );

		foreach ( $this->getAttributes() as $a )
		{
			$a->compile( $compiler );
		}

		$compiler->writeBody( ' ): ?>' );

		foreach( $this->getChildren() as $child )
		{
			$child->compile( $compiler );
		}

		$compiler->writeBody( '<?php endif; ?>' );
	}
}