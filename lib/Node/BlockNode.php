<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class BlockNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'block' ) )
		{
			$parser->insert( new static() );
			$parser->traverseUp();
			$parser->advance();

			if( $parser->skip( Token::T_SYMBOL, '(' ) )
			{
				if( ExpressionNode::parse( $parser ) )
				{
					$parser->setAttribute();
				}
			}

			$parser->skip( Token::T_SYMBOL, ')' );

			if( $parser->accept( Token::T_IDENT, 'append' ) || $parser->accept( Token::T_IDENT, 'prepend' ) )
			{
				$parser->insert( new OptionNode( $parser->getCurrentToken()->getValue() ) );
				$parser->setAttribute();
				$parser->advance();
			}

			if( $parser->skip( Token::T_CLOSING_TAG ) )
			{
				$parser->restartParse();
			}

			if( $parser->skip( Token::T_IDENT, '/block' ) )
			{
				if( $parser->skip( Token::T_CLOSING_TAG ) )
				{
					$parser->traverseDown();
					$parser->restartParse();
				}
			}

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$attributeName = $this->getAttribute( 0 );
		$subcompiler = new Compiler();
		$blockName = $subcompiler->compile( $attributeName );

		$blockFunction = 'setBlock';

		if( $this->getAttribute( 1 ) )
		{
			$option = $this->getAttribute( 1 );

			if( $option->getValue() === 'prepend' )
			{
				$blockFunction = 'prependBlock';
			}
			elseif( $option->getValue() === 'append' )
			{
				$blockFunction = 'appendBlock';
			}
		}

		// set block
		$compiler->writeBody( '<?php $env->' . $blockFunction . '( ' );
		$compiler->writeBody( $blockName );
		$compiler->writeBody( ', function() use ( &$env ) { ?>' );
		foreach( $this->getChildren() as $c )
		{
			$subcompiler = new Compiler();
			$compiler->writeBody( $subcompiler->compile( $c ) );
		}
		$compiler->writeBody( '<?php } ); ?>' );
	}
}