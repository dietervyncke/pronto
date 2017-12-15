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
	const REGEX_T_SYMBOL = '[\(\)\,]';
	const REGEX_T_NUMBER = '[0-9.]';

	const REGEX_T_STRING_DELIMITER = '[\"\']'; // " '

	const REGEX_T_VAR = '^[a-zA-Z._-]+';
	const REGEX_T_VAR_START = '\?';
	const REGEX_T_GLOBAL_VAR = '\=';
	const REGEX_T_LOCAL_VAR = '\-';
	const REGEX_T_OP = '[\+\-\*\?]';

	const T_TEXT = 'T_TEXT';
	const T_OPENING_TAG = 'T_OPENING_TAG';
	const T_CLOSING_TAG = 'T_CLOSING_TAG';
	const T_STRING = 'T_STRING';
	const T_IDENT = 'T_IDENT';
	const T_GLOBAL_VAR = 'T_GLOBAL_VAR';
	const T_LOCAL_VAR = 'T_LOCAL_VAR';
	const T_PARAM_OPENING_TAG = 'T_PARAM_OPENING_TAG';
	const T_PARAM_CLOSING_TAG = 'T_PARAM_CLOSING_TAG';
	const T_NUMBER = 'T_NUMBER';
	const T_SYMBOL = 'T_SYMBOL';
	const T_OP = 'T_OP';

	private static $tokentypes = [
		self::T_TEXT => 'T_TEXT',
		self::T_OPENING_TAG => 'T_OPENING_TAG',
		self::T_CLOSING_TAG => 'T_CLOSING_TAG',
		self::T_STRING => 'T_STRING',
		self::T_IDENT => 'T_IDENT',
		self::T_GLOBAL_VAR => 'T_GLOBAL_VAR',
		self::T_LOCAL_VAR => 'T_LOCAL_VAR',
		self::T_OP => 'T_OP',
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
		return $this->getType() . '(' . $this->getValue() . ')' . "\n";
	}
}