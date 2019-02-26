<?php

namespace Pronto\Exporter;

use Pronto\Contract\ExporterInterface;
use Pronto\Contract\RuntimeInterface;
use Pronto\Contract\StorageInterface;

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
		$json = json_encode($this->runtime->getState(), JSON_PRETTY_PRINT);

		$this->storage->put($json);
	}
}