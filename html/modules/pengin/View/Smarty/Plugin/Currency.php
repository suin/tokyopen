<?php
class Pengin_View_Smarty_Plugin_Currency implements Pengin_View_Smarty_PluginInterface,
                                                    Pengin_View_Smarty_PluginModifierInterface
{
	public static function getType()
	{
		return self::TYPE_MODIFIER;
	}

	public static function getName()
	{
		return 'currency';
	}

	public static function run($number)
	{
		$decimals     = '.';
		$decPoint     = ',';
		$thousandsSep = ',';
		return number_format($number, $decimals, $decPoint, $thousandsSep);
	}
}
