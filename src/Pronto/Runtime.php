<?php

namespace Pronto;

use Pronto\Contract\RuntimeInterface;

class Runtime implements RuntimeInterface
{
	private $onChange;
	private $currentState = [];
	private $currentScopeId = 0;

	public function __construct()
	{
		$this->currentState['global'] = [
			'vars' => [],
			'scopes' => [],
		];
	}

	public function setGlobalVariable(string $name, $value): void
	{
		$this->currentState['global']['vars'][$name] = $value;

		if ($this->onChange) {
			call_user_func($this->onChange);
		}
	}

	public function getGlobalVariable(string $name)
	{
		return $this->currentState['global']['vars'][$name];
	}

	public function hasGlobalVariable(string $name): bool
	{
		return isset($this->currentState['global']['vars'][$name]);
	}

	public function getGlobalVariables(): array
	{
		return $this->currentState['global']['vars'];
	}

	public function setLocalVariable(string $name, $value): void
	{
		$this->currentState['global']['scopes'][(string) $this->currentScopeId]['vars'][$name] = $value;

		if ($this->onChange) {
			call_user_func($this->onChange);
		}
	}

	public function getLocalVariable(string $name)
	{
		return $this->currentState['global']['scopes'][(string) $this->currentScopeId]['vars'][$name];
	}

	public function hasLocalVariable(string $name): bool
	{
		return isset($this->currentState['global']['scopes'][(string) $this->currentScopeId]['vars'][$name]);
	}

	public function allocateScope(): void
	{
		$this->currentScopeId++;

		if (! isset($this->currentState['global']['scopes'][(string) $this->currentScopeId])) {
			$this->currentState['global']['scopes'][(string) $this->currentScopeId] = false;
		}

		if ($this->onChange) {
			call_user_func($this->onChange);
		}
	}

	public function createScope(): void
	{
		if (! isset($this->currentState['global']['scopes'][(string) $this->currentScopeId])) {
			$this->currentState['global']['scopes'][(string) $this->currentScopeId] = [
				'vars' => [],
			];
		}

		if ($this->onChange) {
			call_user_func($this->onChange);
		}
	}

	public function nextScopeIsAllocated(): bool
	{
		$nextScopeId = (string) ($this->currentScopeId + 1);

		return isset($this->currentState['global']['scopes'][$nextScopeId]);
	}

	public function nextScopeIsCreated(): bool
	{
		$nextScopeId = $this->currentScopeId + 1;

		return isset($this->currentState['global']['scopes'][(string) $nextScopeId]) && $this->currentState['global']['scopes'][(string) $nextScopeId] !== false;
	}

	public function onChange(callable $call)
	{
		$this->onChange = $call;
	}

	public function getState(): array
	{
		return $this->currentState;
	}

	public function setState(array $state): void
	{
		$this->currentState = $state;
	}
}