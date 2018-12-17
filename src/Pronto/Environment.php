<?php

namespace Pronto;

use Pronto\Buffer\DefaultBuffer;
use Pronto\Contract\BufferInterface;
use Pronto\Contract\InputInterface;
use Pronto\Contract\OutputInterface;

class Environment
{
	private $cwd;
	private $runPath;
	private $writePath;

	private $runtime;
	private $buffer;
	private $output;
	private $input;

	private static $id = 0;

	public function __construct(Runtime $runtime, BufferInterface $buffer, OutputInterface $output, InputInterface $input, $cwd, $runPath, $writePath)
	{
		$this->runtime = $runtime;
		$this->buffer = $buffer;
		$this->output = $output;
		$this->input = $input;

		$this->cwd = $cwd;
		$this->runPath = $runPath;
		$this->writePath = $writePath;
	}

	public function getLocalVariable(string $name, array $options = []): string
	{
		if ($this->runtime->hasLocalVariable($name)) {
			return $this->runtime->getLocalVariable($name);
		}

		$value = (count($options) ? $this->input->select($name, $options) : $this->input->read($name));
		$this->runtime->setLocalVariable($name, $value);
		return $value;
	}

	public function getGlobalVariable(string $name, array $options = []): string
	{
		if ($this->runtime->hasGlobalVariable($name)) {
			return $this->runtime->getGlobalVariable($name);
		}

		$value = (count($options) ? $this->input->select($name, $options) : $this->input->read($name));
		$this->runtime->setGlobalVariable($name, $value);
		return $value;
	}

	public function setGlobalVariable(string $name, $value)
	{
		$this->runtime->setGlobalVariable($name, $value);
	}

	public function setLocalVariable(string $name, $value)
	{
		$this->runtime->setLocalVariable($name, $value);
	}

	public function repeat($closure, $title = 'Repeat again?')
	{
		$this->input->write('Entering repeat statement');

		while (true) {

			$this->runtime->clearLocalVariables();

			if (!$this->input->confirm($title)) {
				$this->input->write('Exiting repeat');
				break;
			}

			call_user_func($closure);
		}
	}

	public function writeFile($closure, $filename)
	{
		$dir = dirname($this->writePath . '/' . $filename);

		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}

		$output = $this->buffer->read();
		$this->buffer->clear();

		call_user_func($closure);
		file_put_contents($this->writePath . '/' . $filename, $this->buffer->read());

		$this->buffer->clear();
		$this->buffer->write($output);
	}

	public function includeTemplate($filename)
	{
		$filename =  $this->runPath.'/'.$filename;

		if (file_exists($filename)) {

			$lexer = new \Pronto\Lexer();
			$tokens = $lexer->tokenize(file_get_contents($filename));

			$parser = new \Pronto\Parser($tokens);
			$ast = $parser->parse();

			$compiler = new \Pronto\Compiler();
			$compiled = $compiler->compile($ast);

			// create a new runtime and pass it to the environment
			$runtime = new \Pronto\Runtime();
			$buffer = new DefaultBuffer();
			$environment = new \Pronto\Environment($runtime, $buffer, $this->output, $this->input, $this->cwd, $this->runPath, $this->writePath);

			// execute the compiled code
			$environment->execute($compiled);
		}
	}

	public function execute(string $code)
	{
		self::$id++;

		$tempFilename = $this->cwd . '/compiled-' . self::$id . '.php';

		file_put_contents($tempFilename, $code);

		\Pronto\Helpers\File\scopedRequire($tempFilename, [
			'env' => $this,
		]);

		unlink($tempFilename);

		$this->output->write($this->buffer->read());
	}

	public function write(string $string)
	{
		$this->buffer->write($string);
	}
}