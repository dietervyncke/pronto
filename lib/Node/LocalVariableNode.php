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

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( '<?php $env->getLocalVariable( \'' . $this->name . '\' ); ?>' );
		$compiler->writeBody( '$env->printLocalVariable( \'' . $this->name . '\' )' );
	}
}