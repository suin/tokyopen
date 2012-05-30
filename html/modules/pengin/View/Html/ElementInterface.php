<?php
interface Pengin_View_Html_ElementInterface
{
	/**
	 * タグを生成する
	 * 
	 * @access public
	 * @param array $parameters 設定情報
	 * @return string HTMLタグ
	 */
	public static function render(array $parameters = array());
}
