<?php
/**
 * {header_doc}
 */

interface Pengin_Form_Property_HtmlInterface
{
	/**
	 * HTML出力用のコールバック関数を返す.
	 * 
	 * @access public
	 * @return string|array コールバック関数
	 */
	public function getHtmlRenderer();

	/**
	 * HTMLの設定情報を返す.
	 * 
	 * @access public
	 * @return array key-value形式の設定情報
	 */
	public function getHtmlParameters();
}
