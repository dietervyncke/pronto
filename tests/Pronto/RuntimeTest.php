<?php

namespace Tests\Pronto;

use lib\Runtime;
use PHPUnit\Framework\TestCase;

class RuntimeTest extends TestCase
{
	private $runtime;

	public function setup()
	{
		$this->runtime = new Runtime();
		$this->runtime->setGlobalVariable('variable name', 'variable value');
		$this->runtime->setLocalVariable('variable name', 'variable value');
	}

	public function testSetAndGetGlobalVariable()
	{
		$this->runtime->setGlobalVariable('new variable name', 'variable value');
		$this->assertEquals('variable value', $this->runtime->getGlobalVariable('new variable name'));
	}

	public function testCheckRuntimeHasGlobalVariableShouldReturnFalse()
	{
		$this->assertFalse($this->runtime->hasGlobalVariable('undefined variable'));
	}

	public function testHasGlobalVariableReturnsTrue()
	{
		$this->assertTrue($this->runtime->hasGlobalVariable('variable name'));
	}

	public function testSetOverwritesExistingGlobalVariable()
	{
		$this->runtime->setGlobalVariable('variable name to overwrite', 'initial variable value');
		$this->runtime->setGlobalVariable('variable name to overwrite', 'overwritten variable value');

		$this->assertEquals('overwritten variable value', $this->runtime->getGlobalVariable('variable name to overwrite'));
	}

	public function testSetAndGetLocalVariable()
	{
		$this->runtime->setLocalVariable('new variable name', 'variable value');
		$this->assertEquals('variable value', $this->runtime->getLocalVariable('new variable name'));
	}

	public function testHasLocalVariableShouldReturnFalse()
	{
		$this->assertFalse($this->runtime->hasLocalVariable('undefined variable'));
	}

	public function testHasLocalVariableReturnsTrue()
	{
		$this->assertTrue($this->runtime->hasLocalVariable('variable name'));
	}

	public function testSetOverwritesExistingLocalVariable()
	{
		$this->runtime->setLocalVariable('variable name to overwrite', 'initial variable value');
		$this->runtime->setLocalVariable('variable name to overwrite', 'overwritten variable value');

		$this->assertEquals('overwritten variable value', $this->runtime->getLocalVariable('variable name to overwrite'));
	}

	public function testClearLocalVariables()
	{
		$this->assertTrue($this->runtime->hasLocalVariable('variable name'));

		$this->runtime->clearLocalVariables();

		$this->assertFalse($this->runtime->hasLocalVariable('variable name'));
	}

	public function testClearGlobalVariables()
	{
		$this->assertTrue($this->runtime->hasGlobalVariable('variable name'));

		$this->runtime->clearGlobalVariables();

		$this->assertFalse($this->runtime->hasGlobalVariable('variable name'));
	}
}