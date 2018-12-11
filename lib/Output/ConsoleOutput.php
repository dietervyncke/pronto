<?php

namespace lib\Output;

use lib\Contract\OutputInterface;

class ConsoleOutput implements OutputInterface
{
	public function write(string $string)
	{
		echo $string;
	}
}