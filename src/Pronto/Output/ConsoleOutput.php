<?php

namespace Pronto\Output;

use Pronto\Contract\OutputInterface;

class ConsoleOutput implements OutputInterface
{
	public function write(string $string)
	{
		echo $string;
	}
}