<?php

namespace Pronto\Contract;

interface BufferInterface
{
	public function write(string $string): void;
	public function read(): string;
	public function clear(): void;
}