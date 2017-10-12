<?php

require_once 'lib/Lexer.php';
require_once 'lib/TokenStream.php';
require_once 'lib/Token.php';
require_once 'lib/Parser.php';
require_once 'lib/Compiler.php';
require_once 'lib/Node/Node.php';
require_once 'lib/Node/RootNode.php';
require_once 'lib/Node/VariableNode.php';
require_once 'lib/Node/RepeatNode.php';
require_once 'lib/Node/TextNode.php';

// Eerst lexen
$lexer = new \lib\Lexer();
$tokens = $lexer->tokenize( file_get_contents( 'templates/offerte-template01.tpl' ) );

$parser = new \lib\Parser( $tokens );
$ast = $parser->parse();

$compiler = new \lib\Compiler();
$output = $compiler->compile( $ast );

file_put_contents( 'output.php', $output );
file_put_contents( 'mijn-offerte.html', require 'output.php' );