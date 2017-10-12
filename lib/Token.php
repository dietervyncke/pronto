<?php

namespace lib;

class Token
{
	private $type;
	private $value;

	const REGEX_T_EOL = '[\n\r]';
	const REGEX_T_OPENING_TAG = '\{';
	const REGEX_T_CLOSING_TAG = '\}';
	const REGEX_T_IDENT = '[a-zA-Z\-\_\/]';
	const REGEX_T_ASSIGN = '[=]';

	const REGEX_T_STRING_DELIMITER = '[\"\']'; // " '

	const REGEX_T_VAR = '^[a-zA-Z._-]+';
	const REGEX_T_VAR_START = '\?';

	const T_TEXT = 'T_TEXT';
	const T_OPENING_TAG = 'T_OPENING_TAG';
	const T_CLOSING_TAG = 'T_CLOSING_TAG';
	const T_STRING = 'T_STRING';
	const T_IDENT = 'T_IDENT';
	const T_VAR = 'T_VAR';

	private static $tokentypes = [
		self::T_TEXT => 'T_TEXT',
		self::T_OPENING_TAG => 'T_OPENING_TAG',
		self::T_CLOSING_TAG => 'T_CLOSING_TAG',
		self::T_STRING => 'T_STRING',
		self::T_IDENT => 'T_IDENT',
		self::T_VAR => 'T_VAR',
	];

	public function __construct( $type, $value )
	{
		$this->type = $type;
		$this->value = $value;
	}

	public function getName()
	{
		if( isset( self::$tokentypes[ $this->type ] ) )
		{
			return self::$tokentypes[ $this->type ];
		}

		return 'T_UNKNOWN';
	}

	public function getType()
	{
		return $this->type;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function __toString()
	{
		return $this->getType() . $this->getValue() . "\n";
	}
}