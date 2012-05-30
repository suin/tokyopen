<?php
/**
 * プリロードでコントローラを呼び出すための関数群
 */

require_once PENGIN_PATH.'/Pengin.php';

function pengin_call_preload_dispatcher($controller, $action, $dirname)
{
	$options = array(
		'controller' => $controller,
		'action'     => $action,
		'dirname'    => $dirname,
	);

	try
	{
		ob_start();
		$pengin =& Pengin::getInstance();
		$pengin->main('preload', $options);
		$content = ob_get_contents();
		ob_end_clean();
	}
	catch ( RuntimeException $e )
	{
		ob_flush();
		trigger_error($e->getMessage(), E_USER_WARNING);
		return false;
	}
	catch ( Exception $e )
	{
		ob_flush();
		return false;
	}

	return $content;
}
