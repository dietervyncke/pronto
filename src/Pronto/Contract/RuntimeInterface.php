<?php

namespace Pronto\Contract;

interface RuntimeInterface
{
	public function setGlobalVariable(string $name, $value): void;
	public function getGlobalVariable(string $name): string;
	public function hasGlobalVariable(string $name): bool;

	public function setLocalVariable(string $name, $value): void;
	public function getLocalVariable(string $name): string;
	public function hasLocalVariable(string $name): bool;

	public function clearGlobalVariables(): void;
	public function clearLocalVariables(): void;

	public function getGlobalVariables(): array;
}