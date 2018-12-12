<?php

namespace lib;

use lib\Contract\OutputInterface;

class Runtime
{
	private static $id = 0;

	private $output;

	private $cwd;

	private $runPath;

	public function __construct(OutputInterface $output, $cwd, $runPath)
	{
		$this->output = $output;
		$this->cwd = $cwd;
		$this->runPath = $runPath;
	}

	public function execute(Environment $env, $compiled)
	{
		self::$id++;

		$tempFilename = $this->cwd . '/compiled-' . self::$id . '.php';

		file_put_contents( $tempFilename, $compiled );

		\lib\Helpers\File\scopedRequire( $tempFilename, [
			'env' => $env,
		] );

		unlink( $tempFilename );

		$this->output->write( $env->render() );
	}
}