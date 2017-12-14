<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class GlobalVariableNode extends Node
{
	private $name;

	public function __construct( $name )
	{
		$this->name = $name;
	}

	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_GLOBAL_VAR ) )
		{
			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();

			if( $parser->skip( Token::T_SYMBOL, '(' ) )
			{
				$parser->traverseUp();

				if( ParameterNode::parse( $parser ) )
				{
					$parser->setAttribute();
				}

				$parser->traverseDown();
			}

			$parser->skip( Token::T_SYMBOL, ')' );

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeHead( '<?php $env->setGlobalVariable( \'' . $this->name . '\'' );

		if( count( $this->getAttributes() ) )
		{
			$compiler->writeHead( ', ' );
		}

		foreach ( $this->getAttributes() as $a )
		{
			$subcompiler = new Compiler();
			$compiler->writeHead( $subcompiler->compile( $a ) );
		}

		$compiler->writeHead( ' ); ?>' );

		$compiler->writeBody( '$env->getGlobalVariable( \'' . $this->name . '\' )' );
	}
}