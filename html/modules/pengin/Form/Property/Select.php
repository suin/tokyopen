<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_Select extends Pengin_Form_Property
                                  implements Pengin_Form_Property_OptionInterface,
                                             Pengin_Form_Property_HtmlInterface
{
	protected $options = array();

	public function describeValue()
	{
		return $this->options[$this->value];
	}

	public function describeValueLocal()
	{
		return t($this->describeValue());
	}

	public function options(array $options)
	{
		$this->options = $options;
		return $this;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function getOptionsLocal()
	{
		$options = array();

		foreach ( $this->options as $key => $value ) {
			$options[$key] = t($value);
		}

		return $options;
	}

	public function validate()
	{
		parent::validate();
		$this->validateOptions();
	}

	public function validateOptions()
	{
		if ( $this->_testInOptions($this->value, $this->options) === false )
		{
			$this->addError(t("Please select {1}.", $this->getLabelLocal()));
		}

		return $this;
	}

	/**
	 * HTML出力用のコールバック関数を返す.
	 * 
	 * @access public
	 * @return string|array コールバック関数
	 */
	public function getHtmlRenderer()
	{
		return array('Pengin_View_Html_Select', 'render');
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
			'name'     => $this->name,
			'selected' => $this->value,
			'options'  => $this->getOptionsLocal(),
		);

		$values = array_merge($values, $this->attributes);

		return $values;
	}

	protected function _testInOptions($value, $options)
	{
		return array_key_exists($value, $options);
	}

	/**
	 * 必須についてバリデーションを行う.
	 * 
	 * @access public
	 * @return Pengin_Form_Property
	 */
	public function validateRequired()
	{
		// 選択形式なので、必須チェックは validateOptions でやる
		return $this;
	}
}
