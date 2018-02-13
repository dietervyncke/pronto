<?php

namespace lib;

class Runtime
{
	private static $id = 0;

	private $cwd;
	private $outputFile;

	public function __construct( $cwd )
	{
		$this->cwd = $cwd;
	}

	public function setOutputFile( $filename )
	{
		$this->outputFile = $filename;
	}

	private function writeOutput( Environment $env )
	{
		$output = $env->getOutput();

		if( ! $this->outputFile )
		{
			echo $output;
			return $output;
		}

		file_put_contents( $this->cwd . '/' . $this->outputFile, $output );
		return $output;
	}

	public function execute( Environment $env, $compiled )
	{
		self::$id++;

		$env->setCwd( $this->cwd );
		$tempFilename = $this->cwd . '/compiled-' . self::$id . '.php';

		file_put_contents( $tempFilename, $compiled );

		\lib\Helpers\File\scopedRequire( $tempFilename, [
			'env' => $env,
		] );

		unlink( $tempFilename );

		return $this->writeOutput( $env );
	}
}