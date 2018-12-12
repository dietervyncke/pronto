<?php

namespace Pronto\Exporter;

use Pronto\Contract\ImporterInterface;
use Pronto\Contract\RuntimeInterface;
use Pronto\Contract\StorageInterface;
use Pronto\Runtime;

class JsonImporter implements ImporterInterface
{
	private $storage;

	public function __construct(StorageInterface $storage)
	{
		$this->storage = $storage;
	}

	public function import(): RuntimeInterface
	{
		$globalVars = json_decode($this->storage->get());

		$runtime = new Runtime();

		foreach ($globalVars as $name => $value) {
			$runtime->setGlobalVariable($name, $value);
		}

		return $runtime;
	}
}