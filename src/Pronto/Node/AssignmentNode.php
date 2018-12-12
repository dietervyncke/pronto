<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Parser;
use Pronto\Token;

class AssignmentNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'var' ) )
		{
			$parser->insert( new static() );
			$parser->traverseUp();
			$parser->advance();

			if( GlobalVariableNode::parse( $parser ) || LocalVariableNode::parse( $parser ) )
			{
				$parser->setAttribute();
			}

			$parser->accept( Token::T_IDENT, 'is' );
			$parser->advance();

			ExpressionNode::parse( $parser );

			$parser->skip( Token::T_CLOSING_TAG );
			$parser->traverseDown();
			$parser->restartParse();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( '<?php $env->setGlobalVariable( ' );

		foreach ( $this->getAttributes() as $a )
		{
			$compiler->writeBody(  '\'' . $a->getName() . '\'' );
		}

		$compiler->writeBody( ', ' );

		foreach ( $this->getChildren() as $c ) {
			$subcompiler = new Compiler();
			$compiler->writeBody( $subcompiler->compile( $c ) );
		}

		$compiler->writeBody( ' ) ?>' );
	}
}

