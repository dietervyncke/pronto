<?php

namespace Pronto\Importer;

use Pronto\Contract\ImporterInterface;
use Pronto\Contract\RuntimeInterface;
use Pronto\Contract\StorageInterface;

class JsonImporter implements ImporterInterface
{
	private $storage;
	private $runtime;

	public function __construct(StorageInterface $storage, RuntimeInterface $runtime)
	{
		$this->storage = $storage;
		$this->runtime = $runtime;
	}

	public function import()
	{
		$state = json_decode($this->storage->get(), true);

		if ($state !== null) {
			$this->runtime->setState($state);
		}
	}
}