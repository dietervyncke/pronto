<?php

namespace Pronto\Contract;

interface RuntimeInterface
{
	public function setGlobalVariable(string $name, $value): void;
	public function getGlobalVariable(string $name);
	public function hasGlobalVariable(string $name): bool;

	public function setLocalVariable(string $name, $value): void;
	public function getLocalVariable(string $name);
	public function hasLocalVariable(string $name): bool;

	public function allocateScope(): void;
	public function createScope(): void;

	public function nextScopeIsAllocated(): bool;
	public function nextScopeIsCreated(): bool;

	public function getGlobalVariables(): array;

	public function getState(): array;
	public function setState(array $state): void;

	public function onChange(callable $call);
}