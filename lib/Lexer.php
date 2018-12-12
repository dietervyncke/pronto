<?php

namespace lib;

class Lexer
{
	private $mode;

	private $input;
	private $stream;

	private $cursor;
	private $line;
	private $end;
	private $lastCharPos;

	private $currentChar;
	private $currentValue;
	private $modeStartChar;

	const MODE_ALL = 0;
	const MODE_INSIDE_TAG = 1;
	const MODE_STRING = 2;
	const MODE_IDENT = 3;
	const MODE_VAR = 4;
	const MODE_PARAM = 5;
	const MODE_NUMBER = 6;

	public function tokenize( $input )
	{
		$this->prepare( $input );

		while( $this->cursor < $this->end )
		{
			$this->currentChar = $this->input[ $this->cursor ];

			if( preg_match( '@' . Token::REGEX_T_EOL . '@', $this->currentChar ) )
			{
				++$this->line;
			}

			switch ( $this->mode )
			{
				case self::MODE_ALL:
					$this->lexAll();
					break;
				case self::MODE_INSIDE_TAG:
					$this->lexInsideTag();
					break;
				case self::MODE_IDENT:
					$this->lexIdent();
					break;
				case self::MODE_VAR:
					$this->lexVar();
					break;
				case self::MODE_STRING:
					$this->lexString();
					break;
				case self::MODE_NUMBER:
					$this->lexNumber();
					break;
			}
		}

		return $this->stream;
	}

	private function prepare( $input )
	{
		$this->input = str_replace( [ "\n\r", "\r" ], "\n", $input );

		$this->stream = new TokenStream();
		$this->setMode( self::MODE_ALL );

		$this->line = 1;
		$this->cursor = 0;
		$this->end = strlen( $this->input );
		$this->lastCharPos = $this->end - 1;

		$this->currentChar = '';
		$this->currentValue = '';
		$this->modeStartChar = '';
	}

	private function lexAll()
	{
		// only text is found as input
		if( $this->cursor + 1 === $this->end )
		{
			$this->stream->addToken( new Token( Token::T_TEXT, $this->currentValue.$this->currentChar ) );
			$this->advanceCursor(); // escape while-loop

			return ;
		}

		// start opening tag {{
		if( preg_match( '@' . Token::REGEX_T_OPENING_TAG . '@', $this->currentChar ) && preg_match( '@' . Token::REGEX_T_OPENING_TAG . '@', $this->getNextChar() ) )
		{
			// save all stored chars before the opening tag
			if( $this->currentValue !== '' )
			{
				$this->stream->addToken( new Token( Token::T_TEXT, $this->currentValue ) );
				$this->currentValue = '';
			}

			$this->stream->addToken( new Token( Token::T_OPENING_TAG, '' ) );
			$this->advanceCursor( 2 );
			$this->mode = self::MODE_INSIDE_TAG;

			return ;
		}

		// temp value for token
		$this->currentValue .= $this->currentChar;
		$this->advanceCursor();
	}

	private function lexInsideTag()
	{
		//closing tag
		if( preg_match( '@'. Token::REGEX_T_CLOSING_TAG . '@', $this->currentChar ) && preg_match( '@'. Token::REGEX_T_CLOSING_TAG . '@', $this->getNextChar() ) )
		{
			$this->stream->addToken( new Token( Token::T_CLOSING_TAG, '' ) );
			$this->currentValue = '';
			$this->advanceCursor( 2 );
			$this->setMode( self::MODE_ALL );

			return;
		}
		elseif( preg_match( '@'. Token::REGEX_T_IDENT . '@', $this->currentChar ) )
		{
			$this->setMode( self::MODE_IDENT );

			return;
		}
		elseif( preg_match( '@' . Token::REGEX_T_NUMBER . '@', $this->currentChar ) )
		{
			$this->setMode( self::MODE_NUMBER );

			return;
		}
		elseif( preg_match( '@' . Token::REGEX_T_VAR_START . '@', $this->currentChar ) )
		{
			$this->setMode( self::MODE_VAR );
			$this->advanceCursor();

			return;
		}
		elseif( preg_match( '@'. Token::REGEX_T_STRING_DELIMITER . '@', $this->currentChar ) )
		{
			$this->setMode( self::MODE_STRING );
			$this->modeStartChar = $this->currentChar;
			$this->advanceCursor();

			return;
		}
		elseif( preg_match( '@' . Token::REGEX_T_OP . '@', $this->currentChar ) )
		{
			$this->stream->addToken( new Token( Token::T_OP, $this->currentChar ) );
		}
		elseif( preg_match( '@' . Token::REGEX_T_SYMBOL . '@', $this->currentChar ) )
		{
			$this->stream->addToken( new Token( Token::T_SYMBOL, $this->currentChar ) );
		}

		$this->advanceCursor();
	}

	private function lexIdent()
	{
		//store chars until
		if( !preg_match( '@' . Token::REGEX_T_IDENT . '@', $this->currentChar ) )
		{
			$this->stream->addToken( new Token( Token::T_IDENT, $this->currentValue ) );
			$this->currentValue = '';
			$this->setMode( self::MODE_INSIDE_TAG );

			return;
		}

		$this->currentValue .= $this->currentChar;
		$this->advanceCursor();
	}

	private function lexVar()
	{
		$variableType = Token::T_GLOBAL_VAR;

		if ( preg_match( '@' . Token::REGEX_T_LOCAL_VAR . '@', $this->currentChar ) )
		{
			$variableType = Token::T_LOCAL_VAR;
		}

		$this->advanceCursor();

		if ( preg_match( '@' . Token::REGEX_T_VAR . '@', substr( $this->input, $this->cursor ), $matches ) )
		{
			$variableName = $matches[ 0 ];
			$this->stream->addToken( new Token( $variableType, $variableName ) );
			$this->advanceCursor( strlen( $variableName ) );
			$this->setMode( self::MODE_INSIDE_TAG );

			return;
		}
	}

	private function lexNumber()
	{
		if ( !preg_match( '@' . Token::REGEX_T_NUMBER . '@', $this->currentChar ) )
		{
			$this->stream->addToken( new Token( Token::T_NUMBER, $this->currentValue ) );
			$this->currentValue = '';
			$this->setMode( self::MODE_INSIDE_TAG );

			return;
		}

		$this->currentValue .= $this->currentChar;
		$this->advanceCursor();
	}

	private function lexString()
	{
		// until found same as startChar
		if( $this->currentChar === $this->modeStartChar )
		{
			$this->advanceCursor();
			$this->stream->addToken( new Token( Token::T_STRING, $this->currentValue ) );
			$this->currentValue = '';
			$this->setMode( self::MODE_INSIDE_TAG );

			return;
		}

		$this->currentValue .= $this->currentChar;
		$this->advanceCursor();
	}

	private function getNextChar()
	{
		return $this->input[ $this->cursor + 1 ];
	}

	private function setCursor( $n )
	{
		$this->cursor = $n;
	}

	private function setMode( $mode )
	{
		$this->mode = $mode;
	}

	private function advanceCursor( $n = 1 )
	{
		$this->setCursor( $this->cursor + $n );
	}
}