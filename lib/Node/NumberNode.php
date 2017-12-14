<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

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
		$compiler->writeBody( '<?php $env->write(\'' . $this->text . '\'); ?>' );
	}
}