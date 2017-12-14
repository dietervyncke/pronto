<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class PrintNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( ExpressionNode::parse( $parser ) )
		{
			$parser->wrap( new static() );
			$parser->traverseDown();
		}

		if ( $parser->skip( Token::T_CLOSING_TAG ) ) {
			$parser->restartParse();
		}
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody('<?php ');

		$compiler->writeBody( '$env->write(' );

		foreach( $this->getChildren() as $child )
		{
			$child->compile( $compiler );
		}

		$compiler->writeBody( ');' );

		$compiler->writeBody( ' ?>' );
	}
}