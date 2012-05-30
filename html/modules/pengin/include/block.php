<?php
require_once PENGIN_PATH.'/Pengin.php';

if ( isset($dirname) and is_string($dirname) and isset($dirname[0]) and function_exists($dirname.'_block_show') === false )
{
	eval('
	function '.$dirname.'_block_show($blockOptions)
	{
		return pengin_block_show($blockOptions, "'.$dirname.'");
	}
	
	function '.$dirname.'_block_edit($blockOptions)
	{
		return pengin_block_edit($blockOptions, "'.$dirname.'");
	}
	');
}

if ( defined('PENGIN_BLOCK_FUNCTION_LOADED') === false )
{
	define('PENGIN_BLOCK_FUNCTION_LOADED', true);

	function pengin_block_show($blockOptions, $dirname)
	{
		$options = pengin_block_get_options('show', $blockOptions, $dirname);
	
		try
		{
			$content = pengin_block_get_content($options);
		}
		catch ( RuntimeException $e )
		{
			trigger_error($e->getMessage(), E_USER_NOTICE);
			ob_end_clean();
			return false;
		}
		catch ( Exception $e )
		{
			ob_end_clean();
			return false;
		}
	
		return array('content' => $content);
	}
	
	function pengin_block_edit($blockOptions, $dirname)
	{
		$options = pengin_block_get_options('edit', $blockOptions, $dirname);
	
		try
		{
			$content = pengin_block_get_content($options);
		}
		catch ( RuntimeException $e )
		{
			trigger_error($e->getMessage(), E_USER_NOTICE);
			return false;
		}
		catch ( Exception $e )
		{
			return false;
		}
	
		return $content;
	}
	
	function pengin_block_get_content(array $options)
	{
		ob_start();
		$pengin =& Pengin::getInstance();
		$pengin->main('block', $options);
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	function pengin_block_get_options($action, array $blockOptions, $dirname)
	{
		return array(
			'controller' => $blockOptions[0],
			'action'     => $action,
			'dirname'    => $dirname,
			'options'    => $blockOptions,
		);
	}
}
