<?php

// parse CLI options

$opts = getopt('i:o:s:', [], $lastIndex );

$inputPath = ( isset( $opts[ 'i' ] ) ? $opts[ 'i' ] : NULL );
$outputPath = ( isset( $opts[ 'o' ] ) ? $opts[ 'o' ] : NULL );
$inputSource = ( isset( $opts[ 's' ] ) ? $opts[ 's' ] : NULL );

// require the needed files

require_once 'util/debug.inc.php';
require_once 'vendor/autoload.php';

require_once 'lib/Helper/File.php';

require_once 'lib/Lexer.php';
require_once 'lib/TokenStream.php';
require_once 'lib/Token.php';
require_once 'lib/Parser.php';
require_once 'lib/Compiler.php';
require_once 'lib/Environment.php';
require_once 'lib/Runtime.php';

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
require_once 'lib/Node/LogicalOperatorNode.php';
require_once 'lib/Node/OperatorNode.php';
require_once 'lib/Node/NumberNode.php';
require_once 'lib/Node/ParameterNode.php';
require_once 'lib/Node/IncludeNode.php';
require_once 'lib/Node/AssignmentNode.php';

$source = ( $inputPath ? file_get_contents( getcwd() . '/' . $inputPath ) : $inputSource );

// lex
$lexer = new \lib\Lexer();
$tokens = $lexer->tokenize( $source );

// parse
$parser = new \lib\Parser( $tokens );
$ast = $parser->parse();

// compile
$compiler = new \lib\Compiler();
$output = $compiler->compile( $ast );

// execute the compiled code
$runtime = new \lib\Runtime( getcwd() );

if( $outputPath )
{
	$runtime->setOutputFile( $outputPath );
}

$runtime->execute( new \lib\Environment(), $output );