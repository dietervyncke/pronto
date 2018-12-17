<?php

namespace Pronto\Console;

use Pronto\Buffer\DefaultBuffer;
use Pronto\Compiler;
use Pronto\Contract\InputInterface;
use Pronto\Lexer;
use Pronto\Output\ConsoleOutput;
use Pronto\Runtime;

class InteractiveShell
{
	private $output;
	private $input;
	private $runtime;

	private $cwd;
	private $runPath;
	private $writePath;

	public function __construct(InputInterface $input, $cwd, $runPath, $writePath)
	{
		$this->input = $input;
		$this->output = new ConsoleOutput();
		$this->runtime = new Runtime();

		$this->cwd = $cwd;
		$this->runPath = $runPath;
		$this->writePath = $writePath;
	}

	public function start()
	{
		$code = $this->input->read('pronto > ');

		// lex
		$lexer = new Lexer();
		$tokens = $lexer->tokenize($code);

		// parse
		$parser = new \Pronto\Parser($tokens);
		$ast = $parser->parse();

		// compile
		$compiler = new Compiler();
		$code = $compiler->compile($ast);

		// execute the code!
		$environment = new \Pronto\Environment($this->runtime, new DefaultBuffer(), $this->output, $this->input, $this->cwd, $this->runPath, $this->writePath);
		$environment->execute($code);

		$this->start();

		exit;
	}
}