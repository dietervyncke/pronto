<?php

// parse CLI options

$opts = getopt('i:o:s:d:', [], $lastIndex );

$inputPath = ( isset( $opts[ 'i' ] ) ? $opts[ 'i' ] : NULL );
$outputPath = ( isset( $opts[ 'o' ] ) ? $opts[ 'o' ] : NULL );
$inputSource = ( isset( $opts[ 's' ] ) ? $opts[ 's' ] : NULL );
$writeDir = ( isset( $opts[ 'd' ] ) ? $opts[ 'd' ] : NULL );

// require the needed files

require_once 'util/debug.inc.php';
require_once 'vendor/autoload.php';

$cwd = getcwd();
$source = ( $inputPath ? file_get_contents( getcwd() . '/' . $inputPath ) : $inputSource );
$runPath = ( dirname( getcwd() . '/' . $inputPath ) );

// lex
$lexer = new \Pronto\Lexer();
$tokens = $lexer->tokenize( $source );

// parse
$parser = new \Pronto\Parser( $tokens );
$ast = $parser->parse();

// compile
$compiler = new \Pronto\Compiler();
$code = $compiler->compile( $ast );

// execute the compiled code

$output = ($outputPath ? new \Pronto\Output\FileOutput($outputPath) : new \Pronto\Output\ConsoleOutput());
$input = new \Pronto\Input\ConsoleInput();

// create a new runtime and buffer and pass them to the environment

$runtime = new \Pronto\Runtime();
$buffer = new \Pronto\Buffer\DefaultBuffer();
$environment = new \Pronto\Environment($runtime, $buffer, $output, $input, $cwd, $runPath);

// execute the compiled code
$environment->execute($code);