<?php

namespace lib\Helpers\File;

/**
 * @param $filename
 * @param array $vars
 * @return void
 */
function scopedRequire( $filename, $vars = [] ) : void
{
	extract($vars);
	require $filename;
}