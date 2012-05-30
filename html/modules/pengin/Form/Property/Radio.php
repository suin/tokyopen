<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_Radio extends Pengin_Form_Property_Select
{
	/**
	 * HTML出力用のコールバック関数を返す.
	 * 
	 * @access public
	 * @return string|array コールバック関数
	 */
	public function getHtmlRenderer()
	{
		return array('Pengin_View_Html_Radio', 'render');
	}

	/**
	 * HTMLの設定情報を返す.
	 * 
	 * @access public
	 * @return array key-value形式の設定情報
	 */
	public function getHtmlParameters()
	{
		$values = array(
			'type'     => 'radio',
			'name'     => $this->name,
			'checked'  => $this->value,
			'options'  => $this->getOptionsLocal(),
		);

		$values = array_merge($values, $this->attributes);

		return $values;
	}
}
