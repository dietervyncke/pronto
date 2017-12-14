<?php

namespace lib\Node;

use lib\Compiler;
use lib\Parser;
use lib\Token;

class RepeatNode extends Node
{
	public static function parse( Parser $parser )
	{
		if( $parser->accept( Token::T_IDENT, 'repeat' ) )
		{
			$parser->insert( new static() );
			$parser->traverseUp();
			$parser->advance();

			if( $parser->skip( Token::T_CLOSING_TAG ) )
			{
				$parser->restartParse();
			}

			if( $parser->skip( Token::T_IDENT, '/repeat' ) )
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
		$compiler->writeBody( '<?php $env->repeat(function() use ( &$env ) { ?>' );

		foreach( $this->getChildren() as $child )
		{
			$child->compile( $compiler );
		}

		$compiler->writeBody( '<?php }); ?>' );
	}
}