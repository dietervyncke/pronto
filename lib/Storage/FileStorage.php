<?php

namespace lib\Storage;

use lib\Contract\StorageInterface;

class FileStorage implements StorageInterface
{
	private $filename;

	public function __construct(string $filename)
	{
		$this->filename = $filename;
	}

	public function put(string $contents): void
	{
		file_put_contents($this->filename, $contents);
	}

	public function get(): string
	{
		return file_get_contents($this->filename);
	}

	public function delete(): void
	{
		unlink($this->filename);
	}
}