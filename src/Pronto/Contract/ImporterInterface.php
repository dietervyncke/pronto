<?php

namespace Pronto\Contract;

interface ImporterInterface
{
	public function __construct(StorageInterface $storage, RuntimeInterface $runtime);
	public function import();
}