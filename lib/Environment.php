<?php

namespace lib;

use League\CLImate\CLImate;

class Environment
{
	private $cwd;
	private $runPath;

	public function setCwd( $cwd )
	{
		$this->cwd = $cwd;
	}

	public function setRunPath( $runPath )
	{
		$this->runPath = $runPath;
	}

	const COLOR_RED = 'red';
	const COLOR_GREEN = 'green';
	const COLOR_DARK_GRAY = 'darkGray';

	private $output = '';

	private $globalVariables = [];
	private $localVariables = [];
	private $blocks = [];

	private $indent = 0;

	public function setGlobalVariable( $name, $value )
	{
		$this->globalVariables[ $name ] = $value;
	}

	public function getGlobalVariable( $name, $values = NULL )
	{
		if( isset( $this->globalVariables[ $name ] ) )
		{
			return $this->globalVariables[ $name ];
		}

		$this->globalVariables[ $name ] = ( $values ? $this->getOptions( $values, $name ) : $this->readInput( $name ) );

		return $this->globalVariables[ $name ];
	}

	public function getLocalVariable( $name, $values = NULL )
	{
		if( isset( $this->localVariables[ $name ] ) )
		{
			return $this->localVariables[ $name ];
		}

		$this->localVariables[ $name ] = ( $values ? $this->getOptions( $values, $name ) : $this->readInput( $name ) );

		return $this->localVariables[ $name ];
	}

	public function clearLocalVariables()
	{
		$this->localVariables = [];
	}

	public function setBlock( $name, $closure )
	{
		$this->prependBlock( $name, $closure );
		$this->getBlock( $name );
	}

	public function prependBlock( $name, $closure )
	{
		if( isset( $this->blocks[ $name ] ) )
		{
			array_unshift( $this->blocks[ $name ], $closure );
		}
		else
		{
			$this->blocks[ $name ] = [ $closure ];
		}
	}

	public function appendBlock( $name, $closure )
	{
		if( isset( $this->blocks[ $name ] ) )
		{
			$this->blocks[ $name ][] = $closure;
		}
		else
		{
			$this->blocks[ $name ] = [ $closure ];
		}
	}

	public function getBlock( $name )
	{
		foreach( $this->blocks[ $name ] as $closure )
		{
			call_user_func( $closure );
		}
	}

	public function repeat( $closure, $title = 'Repeat again?' )
	{
		$climate = new CLImate();

		$this->indent++;

		$this->writeLine( 'Entering repeat statement', self::COLOR_DARK_GRAY );

		while( TRUE )
		{
			$this->clearLocalVariables();

			$input = $climate->confirm( $title );

			if( !$input->confirmed() )
			{
				$this->writeLine( 'Exiting repeat', self::COLOR_DARK_GRAY );
				break;
			}

			call_user_func( $closure );
		}

		$this->indent--;
	}

	public function writeFile( $closure, $filename )
	{
		$dir = dirname( $this->cwd . '/' . $filename );

		if( ! is_dir( $dir ) )
		{
			mkdir( $dir, 0777, TRUE );
		}

		$output = $this->getOutput();
		$this->output = '';
		call_user_func( $closure );
		file_put_contents( $this->cwd . '/' . $filename, $this->getOutput() );
		$this->output = $output;
	}

	public function includeTemplate( $filename )
	{
		$filename =  $this->runPath . '/' . $filename;

		if( file_exists( $filename ) )
		{
			$lexer = new \lib\Lexer();
			$tokens = $lexer->tokenize( file_get_contents( $filename ) );

			$parser = new \lib\Parser( $tokens );
			$ast = $parser->parse();

			$compiler = new \lib\Compiler();
			$compiled = $compiler->compile( $ast );

			// execute the compiled code
			$runtime = new \lib\Runtime( $this->cwd, $this->runPath );
			$runtime->execute( $this, $compiled );
		}
	}

	private function getOptions( $values, $title = 'Select an option' )
	{
		while( TRUE )
		{
			$input = $this->printRadioButtonList( $title, $values );
			$response = $input->prompt();

			if( $response )
			{
				return $response;
			}
		}

		return NULL;
	}

	public function write( $text )
	{
		$this->output .= $text;
	}

	public function getOutput()
	{
		return $this->output;
	}

	// CLI helpers

	private function writeLine( $string, $color )
	{
		$climate = new CLImate();
		$climate->$color()->bold( $this->getIndentString() . $string );
	}

	private function getIndentString()
	{
		return str_repeat( '>>>', $this->indent ) . ( $this->indent ? ' ' : NULL );
	}

	private function printRadioButtonList( $title, $values, $color = Environment::COLOR_GREEN )
	{
		$climate = new CLImate();
		return $climate->$color()->radio( $title, $values );
	}

	private function readInput( $title, $color = Environment::COLOR_GREEN )
	{
		$climate = new CLImate();
		$input = $climate->$color()->input( $this->getIndentString() . $title );
		return $input->prompt();
	}
}