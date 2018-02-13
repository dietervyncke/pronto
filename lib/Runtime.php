<?php

namespace lib;

class Runtime
{
	private $cwd;
	private $outputFile;

	public function setCwd( $cwd )
	{
		$this->cwd = $cwd . '/';
	}

	public function setOutputFile( $filename )
	{
		$this->outputFile = $filename;
	}

	private function writeOutput( Environment $env )
	{
		if( ! $this->outputFile )
		{
			echo $env->getOutput();
			return;
		}

		file_put_contents( $this->cwd . '/' . $this->outputFile, $env->getOutput() );
	}

	public function execute( Environment $env, $compiled )
	{
		$tempFilename = $this->cwd . '/compiled.php';

		file_put_contents( $tempFilename, $compiled );

		\lib\Helpers\File\scopedRequire( $tempFilename, [
			'env' => $env,
		] );

		unlink( $tempFilename );

		$this->writeOutput( $env );
	}
}