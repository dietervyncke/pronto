<?php

namespace lib;

class Runtime
{
	private $env;

	public function execute( Environment $env, $compiled )
	{
		$this->env = $env;

		// @TODO save the compiled code in a file

		\lib\Helpers\File\scopedRequire( $filename, [
			'env' => $this->env,
		] );
	}
}