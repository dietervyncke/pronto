<?php

namespace Pronto\Node;

use Pronto\Compiler;
use Pronto\Parser;
use Pronto\Token;

class TextNode extends Node
{
	private $text;

	public function __construct( $text )
	{
		$this->text = $text;
	}

	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_TEXT ) )
		{
			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeBody( '<?php $env->write(\'' . str_replace( '\'', '\\\'', $this->text ) . '\'); ?>' );
	}
}