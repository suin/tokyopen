<?php
class Pengin_View_Smarty_Plugin_Url implements Pengin_View_Smarty_PluginInterface,
                                               Pengin_View_Smarty_PluginFunctionInterface
{
	public static function getType()
	{
		return self::TYPE_FUNCTION;
	}

	public static function getName()
	{
		return 'url';
	}

	public static function run(array $params, &$smarty)
	{
		$root =& Pengin::getInstance();
		return $root->url(null, null, $params);
	}
}
