<?php

namespace lib;

class Runtime
{
	private static $id = 0;

	private $cwd;
	private $runPath;
	private $outputFile;

	public function __construct( $cwd, $runPath )
	{
		$this->cwd = $cwd;
		$this->runPath = $runPath;
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
			//echo $output;
			return $output;
		}

		file_put_contents( $this->cwd . '/' . $this->outputFile, $output );
		return $output;
	}

	public function execute( Environment $env, $compiled )
	{
		self::$id++;

		$env->setCwd( $this->cwd );
		$env->setRunPath( $this->runPath );

		$tempFilename = $this->cwd . '/compiled-' . self::$id . '.php';

		file_put_contents( $tempFilename, $compiled );

		\lib\Helpers\File\scopedRequire( $tempFilename, [
			'env' => $env,
		] );

		unlink( $tempFilename );

		return $this->writeOutput( $env );
	}
}