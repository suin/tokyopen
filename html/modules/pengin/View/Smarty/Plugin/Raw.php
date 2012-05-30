<?php
class Pengin_View_Smarty_Plugin_Raw implements Pengin_View_Smarty_PluginInterface,
                                               Pengin_View_Smarty_PluginModifierInterface
{
	public static function getType()
	{
		return self::TYPE_MODIFIER;
	}

	public static function getName()
	{
		return 'raw';
	}

	public static function run($string)
	{
		return htmlspecialchars_decode($string, ENT_QUOTES);
	}
}
