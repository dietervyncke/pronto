<?php

namespace Pronto\Contract;

interface ExporterInterface
{
	public function __construct(StorageInterface $storage, RuntimeInterface $runtime);
	public function export(): void;
}