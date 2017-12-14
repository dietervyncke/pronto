<?php

namespace lib;

class Environment
{
	private $output = '';

	private $globalVariables = [];
	private $localVariables = [];

	private $indent = 0;

	public function getGlobalVariable( $name )
	{
		if( ! isset( $this->globalVariables[ $name ] ) )
		{
			$this->writeLine($name, 1);
			$this->globalVariables[$name] = \readline();
		}
	}

	public function printGlobalVariable( $name )
	{
		if( isset( $this->globalVariables[ $name ] ) )
		{
//			$this->output .= $this->globalVariables[ $name ];
			return $this->globalVariables[ $name ];
		}

		return NULL;
	}

	public function getLocalVariable( $name )
	{
		$this->writeLine( $name, 1 );
		$this->localVariables[ $name ] = \readline();
	}

	public function printLocalVariable( $name )
	{
		if( isset( $this->localVariables[ $name ] ) )
		{
			$this->output .= $this->localVariables[ $name ];
			return $this->localVariables[ $name ];
		}

		return NULL;
	}

	public function clearLocalVariables()
	{
		$this->localVariables = [];
	}

	public function repeat( $closure )
	{
		$this->indent++;

		$this->writeLine( 'Entering repeat statement', 4 );

		while( TRUE )
		{
			$this->clearLocalVariables();

			call_user_func( $closure );

			$this->writeLine( 'Repeat again?', 4 );
			$breakRepeat = \readline();

			if( $breakRepeat !== 'y' )
			{
				$this->writeLine( 'Exiting repeat', 4 );
				break;
			}
		}

		$this->indent--;
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

	private function writeLine( $string, $colorCode )
	{
		echo $this->getIndentString() . chr( 27 ) . "[" . "$colorCode" . "m" . $string . chr( 27 ) . "[0m" . "\n";
	}

	private function getIndentString()
	{
		return str_repeat('>>>', $this->indent ) . ( $this->indent ? ' ' : NULL );
	}
}