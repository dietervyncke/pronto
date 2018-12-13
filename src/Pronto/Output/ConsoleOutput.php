<?php

namespace Pronto\Output;

use League\CLImate\CLImate;
use Pronto\Contract\OutputInterface;

class ConsoleOutput implements OutputInterface
{
	public function write(string $string): void
	{
		$climate = new CLImate();
		$climate->blue($string);
	}
}