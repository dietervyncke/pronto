<?php

namespace util;

function printTokens( \lib\TokenStream $tokens ) {

	foreach( $tokens->getTokens() as $token )
	{
		echo $token . "\n";
	}
}

function printChildren( \lib\Node\Node $node, $indent = '->' )
{
	foreach( $node->getChildren() as $child )
	{
		echo $indent . ' ' . get_class( $child );

		if( $child->getAttributes() )
		{
			echo ' ( ';

			foreach( $child->getAttributes() as $attribute )
			{
				printAttributes( $attribute );
			}

			echo ' )' . "\n";
		}
		else
		{
			echo "\n";
		}

		printChildren( $child, $indent . '->' );
	}
}

function printAttributes( \lib\Node\Node $node )
{
	foreach( $node->getChildren() as $child )
	{
		printChildrenAttributes( $child );
	}
}

function printChildrenAttributes( \lib\Node\Node $node )
{
	foreach( $node->getChildren() as $child )
	{
		echo get_class( $child ) . ', ';

		printChildrenAttributes( $child );
	}
}