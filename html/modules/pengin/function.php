<?php
function t($message)
{
	$args  = func_get_args();
	$dummy = array_shift($args);
	return Pengin::getInstance()->translator->translate($message, $args);
}

function pen_dump()
{
	$root = Pengin::getInstance();
	$args = func_get_args();
	call_user_func_array(array($root, 'dump'), $args);
}
