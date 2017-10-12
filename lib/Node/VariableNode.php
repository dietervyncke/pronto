<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class VariableNode extends Node
{
	private $name;

	public function __construct( $name )
	{
		$this->name = $name;
	}

	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_VAR ) )
		{
			$parser->insert( new static( $parser->getCurrentToken()->getValue() ) );
			$parser->advance();

			if( $parser->skip( Token::T_CLOSING_TAG ) )
			{
				$parser->restartParse();
			}

			return TRUE;
		}

		return FALSE;
	}

	public function compile( Compiler $compiler )
	{
		$compiler->writeHead( '<?php if( ! isset($' . $this->name . ') ) : ?>' );
		$compiler->writeHead( '<?php echo \'' . $this->name . ' \'; $' . $this->name . ' = \readline(); ?>' );
		$compiler->writeHead( '<?php endif; ?>' );
		$compiler->writeBody( '<?php $output .= $' . $this->name . '; ?>' );
	}
}