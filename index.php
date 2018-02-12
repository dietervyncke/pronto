<?php

$opts = getopt('i:o:', [], $lastIndex );

$inputPath = $opts[ 'i' ];
$outputPath = $opts[ 'o' ];

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
require_once 'lib/Node/LogicalOperatorNode.php';
require_once 'lib/Node/OperatorNode.php';
require_once 'lib/Node/NumberNode.php';
require_once 'lib/Node/ParameterNode.php';
require_once 'lib/Node/IncludeNode.php';
require_once 'lib/Node/AssignmentNode.php';

$lexer = new \lib\Lexer();
$tokens = $lexer->tokenize( file_get_contents( $inputPath ) );

$parser = new \lib\Parser( $tokens );
$ast = $parser->parse();

$compiler = new \lib\Compiler();

$output = $compiler->compile( $ast );
$output .= '<?php return $env->getOutput(); ?>';

if( ! file_exists( '../../cache' ) )
{
	mkdir( '../../cache', 0777 );
}

file_put_contents( '../../cache/compiled.php', $output );
file_put_contents( $outputPath, require '../../cache/compiled.php' );
unlink( '../../cache/compiled.php' );