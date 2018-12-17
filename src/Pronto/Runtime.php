<?php

namespace Pronto;

use Pronto\Contract\RuntimeInterface;

class Runtime implements RuntimeInterface
{
	private $globalVars = [];
	private $localVars = [];

	public function setGlobalVariable(string $name, $value): void
	{
		$this->globalVars[$name] = $value;
	}

	public function getGlobalVariable(string $name)
	{
		return $this->globalVars[$name];
	}

	public function hasGlobalVariable(string $name): bool
	{
		return isset($this->globalVars[$name]);
	}

	public function getGlobalVariables(): array
	{
		return $this->globalVars;
	}

	public function setLocalVariable(string $name, $value): void
	{
		$this->localVars[$name] = $value;
	}

	public function getLocalVariable(string $name)
	{
		return $this->localVars[$name];
	}

	public function hasLocalVariable(string $name): bool
	{
		return isset($this->localVars[$name]);
	}

	public function clearGlobalVariables(): void
	{
		$this->globalVars = [];
	}

	public function clearLocalVariables(): void
	{
		$this->localVars = [];
	}
}