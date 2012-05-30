<?php
interface Pengin_View_Smarty_PluginBlockInterface
{
	/**
	 * メイン処理
	 * 
	 * @access public
	 * @static
	 * @param array $params パラメータ
	 * @param mixed $content ブロックの中身
	 * @param object &$smarty Smartyのインスタンス
	 * @param bool &$repeat ブロックの表示回数
	 * @return mixed 実行結果
	 */
	public static function run(array $params, $content, &$smarty, &$repeat);
}
