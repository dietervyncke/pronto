<?php

namespace Pronto\Input;

use League\CLImate\CLImate;
use Pronto\Contract\InputInterface;

class ConsoleInput implements InputInterface
{
	public function read(string $title): string
	{
		$climate = new CLImate();
		$input = $climate->green()->input( $title );
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
		return $climate->radio($string, $values);
	}
}