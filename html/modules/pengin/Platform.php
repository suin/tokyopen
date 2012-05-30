<?php
class Pengin_Platform
{
	public static function &getInstance()
	{
		static $platform = null;

		if ( $platform !== null )
		{
			return $platform;
		}

		if ( defined('PENGIN_STAND_ALONE') )
		{
			$platform = new Pengin_Platform_StandAlone;
			$platform->name = 'StandAlone';
		}
		elseif ( defined('TOKYOPEN') )
		{
			$platform = new Pengin_Platform_TP;
			$platform->name = 'TP';
		}
		elseif ( defined('XOOPS_ORETEKI') )
		{
			$platform = new Pengin_Platform_OretekiXoops;
			$platform->name = 'OretekiXoops';
		}
		elseif ( defined('XOOPS_CUBE_LEGACY') )
		{
			$platform = new Pengin_Platform_XoopsCubeLegacy;
			$platform->name = 'XoopsCubeLegacy';
		}
		elseif ( defined('ICMS_VERSION_NAME') )
		{
			$platform = new Pengin_Platform_ImpressCMS;
			$platform->name = 'ImpressCMS';
		}
		elseif ( strstr(XOOPS_VERSION, 'JPEx') )
		{
			$platform = new Pengin_Platform_XoopsJPEx;
			$platform->name = 'XoopsJPEx';
		}
		elseif ( strstr(XOOPS_VERSION, 'JP') )
		{
			$platform = new Pengin_Platform_Xoops20;
			$platform->name = 'Xoops20';
		}
		elseif ( strstr(XOOPS_VERSION, 'XOOPS 2.[2-4]') )
		{
			$platform = new Pengin_Platform_Xoops22;
			$platform->name = 'Xoops22';
		}
		else
		{
			$platform = new Pengin_Platform_Xoops20;
			$platform->name = 'Xoops20';
		}

		return $platform;
	}
}

?>
