<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_Textarea extends Pengin_Form_Property
                                    implements Pengin_Form_Property_HtmlInterface
{
	/**
	 * HTML出力用のコールバック関数を返す.
	 * 
	 * @access public
	 * @return string|array コールバック関数
	 */
	public function getHtmlRenderer()
	{
		return array('Pengin_View_Html_Textarea', 'render');
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
			'name'  => $this->name,
			'value' => $this->value,
		);

		$values = array_merge($values, $this->attributes);

		return $values;
	}
}
