<?php
/**
 * Smartyのプラグインインタフェース
 */

interface Pengin_View_Smarty_PluginInterface
{
	const TYPE_FUNCTION = 'function';
	const TYPE_MODIFIER = 'modifier';
	const TYPE_BLOCK    = 'block';

	/**
	 * プラグインのタイプを返す
	 * 
	 * @access public
	 * @static
	 * @return string プラグインのタイプ
	 */
	public static function getType();

	/**
	 * プラグインの名前を返す
	 * 
	 * @access public
	 * @static
	 * @return string プラグインの名前
	 */
	public static function getName();
}
