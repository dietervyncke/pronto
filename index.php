<?php

// require the needed files

require_once 'util/debug.inc.php';
require_once 'vendor/autoload.php';

// parse CLI options
$opts = getopt('ai:o:s:d:r:', [], $lastIndex );

$interactiveShell = (isset($opts['a']) ? true : false);
$inputFile = $opts['i'] ?? null;
$outputFile = $opts['o'] ?? null;
$inputSource = $opts['s'] ?? null;
$writeDir = $opts['d'] ?? null;
$runtimeFile = $opts['r'] ?? null;

$cwd = getcwd();
$runPath = (dirname(getcwd().'/'.$inputFile));
$writePath = (getcwd().'/'.$writeDir);

if ($interactiveShell) {
	$shell = new \Pronto\Console\InteractiveShell(new \Pronto\Input\ConsoleInput(), $cwd, $runPath, $writePath);
	$shell->start();
}

$source = ($inputFile ? file_get_contents(getcwd().'/'.$inputFile) : $inputSource);

// lex
$lexer = new \Pronto\Lexer();
$tokens = $lexer->tokenize($source);

// parse
$parser = new \Pronto\Parser($tokens);
$ast = $parser->parse();

// compile
$compiler = new \Pronto\Compiler();
$code = $compiler->compile($ast);

// create output and input
$output = ($outputFile ? new \Pronto\Output\FileOutput($outputFile) : new \Pronto\Output\ConsoleOutput());
$input = new \Pronto\Input\ConsoleInput();

// create and (possibly) import data to runtime
$runtime = new \Pronto\Runtime();

if ($runtimeFile) {

	$importer = new \Pronto\Importer\JsonImporter(new \Pronto\Storage\FileStorage($runtimeFile), $runtime);
	$importer->import();

	$exporter = new \Pronto\Exporter\JsonExporter(new \Pronto\Storage\FileStorage($runtimeFile), $runtime);
	$runtime->onChange(function () use ($exporter) {
		$exporter->export();
	});
}

// create a new runtime and buffer and pass them to the environment
$buffer = new \Pronto\Buffer\DefaultBuffer();
$environment = new \Pronto\Environment($runtime, $buffer, $output, $input, $cwd, $runPath, $writePath);

// execute the compiled code
$environment->execute($code);