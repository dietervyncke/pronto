<?php

namespace Pronto\Contract;

interface InputInterface
{
	public function write(string $string): void;
	public function read(string $string): string;
	public function confirm(string $string): bool;
	public function select(string $string, array $values): string;
}