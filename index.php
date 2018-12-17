<?php

// require the needed files

require_once 'util/debug.inc.php';
require_once 'vendor/autoload.php';

// parse CLI options
$opts = getopt('ai:o:s:d:', [], $lastIndex );

$interactiveShell = (isset($opts['a']) ? true : false);
$inputFile = ( isset( $opts[ 'i' ] ) ? $opts[ 'i' ] : NULL );
$outputFile = ( isset( $opts[ 'o' ] ) ? $opts[ 'o' ] : NULL );
$inputSource = ( isset( $opts[ 's' ] ) ? $opts[ 's' ] : NULL );
$writeDir = ( isset( $opts[ 'd' ] ) ? $opts[ 'd' ] : NULL );

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

// execute the compiled code
$output = ($outputFile ? new \Pronto\Output\FileOutput($outputFile) : new \Pronto\Output\ConsoleOutput());
$input = new \Pronto\Input\ConsoleInput();

// create a new runtime and buffer and pass them to the environment
$runtime = new \Pronto\Runtime();
$buffer = new \Pronto\Buffer\DefaultBuffer();
$environment = new \Pronto\Environment($runtime, $buffer, $output, $input, $cwd, $runPath, $writePath);

// execute the compiled code
$environment->execute($code);