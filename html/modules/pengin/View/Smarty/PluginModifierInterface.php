<?php
/**
 * Smartyの修飾子プラグインインタフェース
 */

interface Pengin_View_Smarty_PluginModifierInterface
{
	/**
	 * メイン処理
	 * 
	 * @access public
	 * @static
	 * @param mixed $value
	 * @return mixed 処理結果
	 */
	public static function run($value);
}
