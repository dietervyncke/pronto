<?php

namespace Pronto\Input;

use League\CLImate\CLImate;
use Pronto\Contract\InputInterface;

class ConsoleInput implements InputInterface
{
	public function read(string $string): string
	{
		$climate = new CLImate();
		$input = $climate->green()->input($string);
		return $input->prompt();
	}

	public function confirm(string $title): bool
	{
		$climate = new CLImate();
		$input = $climate->confirm( $title );

		return $input->confirmed();
	}

	public function write(string $string): void
	{
		$climate = new CLImate();
		$climate->bold($string);
	}

	public function select(string $string, array $values): string
	{
		$climate = new CLImate();
		$input = $climate->radio($string, $values);

		return $input->prompt();
	}
}