<?php

require_once __DIR__ . '/../vendor/autoload.php';

function autoloader($classname)
{
	$classname = ltrim($classname, '\\');
	include_once __DIR__.'/../'.str_replace('\\', '/', $classname).'.php';
}

spl_autoload_register('autoloader');