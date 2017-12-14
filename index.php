<?php

require_once 'util/debug.inc.php';
require_once 'vendor/autoload.php';

require_once 'lib/Lexer.php';
require_once 'lib/TokenStream.php';
require_once 'lib/Token.php';
require_once 'lib/Parser.php';
require_once 'lib/Compiler.php';

require_once 'lib/Node/Node.php';
require_once 'lib/Node/RootNode.php';
require_once 'lib/Node/GlobalVariableNode.php';
require_once 'lib/Node/LocalVariableNode.php';
require_once 'lib/Node/RepeatNode.php';
require_once 'lib/Node/TextNode.php';
require_once 'lib/Node/IfNode.php';
require_once 'lib/Node/ConditionNode.php';
require_once 'lib/Node/ExpressionNode.php';
require_once 'lib/Node/PrintNode.php';
require_once 'lib/Node/StringNode.php';
require_once 'lib/Node/OperatorNode.php';
require_once 'lib/Node/NumberNode.php';
require_once 'lib/Node/ParameterNode.php';

$lexer = new \lib\Lexer();
$tokens = $lexer->tokenize( file_get_contents( 'templates/offer-test-01.tpl' ) );

//util\printTokens( $tokens );

$parser = new \lib\Parser( $tokens );
$ast = $parser->parse();

//util\printChildren($ast);

$compiler = new \lib\Compiler();
$output = $compiler->compile( $ast );

file_put_contents( 'output.php', $output );
file_put_contents( 'mijn-offerte.html', require 'output.php' );
