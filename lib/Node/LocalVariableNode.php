<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class LocalVariableNode extends Node
{
	private $name;

	public function __construct( $name )
	{
		$this->name = $name;
	}

	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_LOCAL_VAR ) )
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
		$compiler->writeBody( '$env->getLocalVariable( \'' . $this->name . '\'' );

		if( count( $this->getAttributes() ) )
		{
			$compiler->writeBody( ', ' );
		}

		foreach ( $this->getAttributes() as $a )
		{
			$subcompiler = new Compiler();
			$compiler->writeBody( $subcompiler->compile( $a ) );
		}

		$compiler->writeBody( ' )' );

//		$compiler->writeBody( '$env->getLocalVariable( \'' . $this->name . '\' ) ' );
	}
}