<?php
/**
 * Smartyの関数プラグインインタフェース
 */

interface Pengin_View_Smarty_PluginFunctionInterface
{
	/**
	 * メイン処理
	 * 
	 * @access public
	 * @static
	 * @param array $params パラメータ
	 * @param mixed &$smarty Smartyのインスタンス
	 * @return mixed 実行結果
	 */
	public static function run(array $params, &$smarty);
}
