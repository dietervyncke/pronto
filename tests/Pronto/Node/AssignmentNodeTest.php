<?php

namespace Tests\Pronto\Node;

use PHPUnit\Framework\TestCase;
use Pronto\Compiler;
use Pronto\Node\AssignmentNode;
use Pronto\Token;

class AssignmentNodeTest extends TestCase
{
	public function testParsingReturnsTrue()
	{
		$this->checkIfParserReturnsTrue('{{ var ?=variable is 10 }}');
		$this->checkIfParserReturnsTrue('{{ var ?=var is "my value" }}');
		$this->checkIfParserReturnsTrue('{{ var ?=something is "my value" + "my other value" }}');
		$this->checkIfParserReturnsTrue('{{ var ?=myVariableName is "my value" + "my other value" }}');
		$this->checkIfParserReturnsTrue('{{ var ?=my_variable_name is "my value" + 5 }}');
	}

	public function testParsingReturnsFalse()
	{
		$this->checkIfParserReturnsFalse('{{ ?=variable 10 }}');
		$this->checkIfParserReturnsFalse('{{ lol ?=var }}');
		$this->checkIfParserReturnsFalse('{{ "my value" }}');
		$this->checkIfParserReturnsFalse('{{ va ?=myVariableName is "" }}');
		$this->checkIfParserReturnsFalse('{{ ar ?=my_variable_name is "my value" + 5 }}');
	}

	public function testCompilingResults()
	{
		$this->checkIfCompilerIsCorrect('{{ var ?=variable is 10 }}', '<?php $env->setGlobalVariable(\'variable\', 10); ?>');
		$this->checkIfCompilerIsCorrect('{{ var ?=my_variable is 1.0 }}', '<?php $env->setGlobalVariable(\'my_variable\', 1.0); ?>');
		$this->checkIfCompilerIsCorrect('{{ var ?=variable is "hello" }}', '<?php $env->setGlobalVariable(\'variable\', \'hello\'); ?>');
		$this->checkIfCompilerIsCorrect('{{ var ?=variable is \'hello\' }}', '<?php $env->setGlobalVariable(\'variable\', \'hello\'); ?>');
	}

	public function checkIfParserReturnsTrue($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertTrue(AssignmentNode::parse($parser));
	}

	public function checkIfParserReturnsFalse($code)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);

		$this->assertFalse(AssignmentNode::parse($parser));
	}

	public function checkIfCompilerIsCorrect($code, $compiled)
	{
		$lexer = new \Pronto\Lexer();
		$tokenStream = $lexer->tokenize($code);

		$parser = new \Pronto\Parser($tokenStream);
		$parser->skip(Token::T_OPENING_TAG);
		AssignmentNode::parse($parser);

		$compiler = new Compiler();

		$this->assertEquals($compiled, $compiler->compile($parser->getScopeNode()->getLastChild()));
	}
}