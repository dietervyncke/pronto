<?php

namespace lib\Exporter;

use lib\Contract\ExporterInterface;
use lib\Contract\RuntimeInterface;
use lib\Contract\StorageInterface;

class JsonExporter implements ExporterInterface
{
	private $storage;
	private $runtime;

	public function __construct(StorageInterface $storage, RuntimeInterface $runtime)
	{
		$this->storage = $storage;
		$this->runtime = $runtime;
	}

	public function export(): void
	{
		$json = json_encode($this->runtime->getGlobalVariables(), true);

		$this->storage->put($json);
	}
}