<?php

namespace Pronto\Contract;

interface StorageInterface
{
	public function put(string $contents): void;
	public function get(): string;
	public function delete(): void;
}