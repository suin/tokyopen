<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_Checkbox extends Pengin_Form_Property
                                    implements Pengin_Form_Property_OptionInterface,
                                               Pengin_Form_Property_HtmlInterface
{
	/**
	 * 入力値をセットする.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return Pengin_Form_Property_Checkbox
	 */
	public function value($value)
	{
		if ( is_array($value) === false ) {
			$value = array();
		}

		$this->value = $value;
		return $this;
	}

	/**
	 * 値を人が分かるような形で表現する.
	 * 
	 * @access public
	 * @return string
	 */
	public function describeValue()
	{
		if ( is_array($this->value) === false ) {
			return ''; // 未選択
		}

		$checked = array();

		foreach ( $this->value as $value ) {
			if ( array_key_exists($value, $this->options) === true ) {
				$checked[] = $this->options[$value];
			}
		}

		$checked = implode(', ', $checked); // TODO 区切り文字を変更可能にしたほうがいい？

		return $checked;
	}

	/**
	 * 入力値を保存可能な書式で返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function exportValue()
	{
		return implode("\n", $this->value);
	}

	/**
	 * 保存可能な書式で入力値をセットする.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return Pengin_Form_Property_Checkbox
	 */
	public function importValue($value)
	{
		$this->value = explode("\n", $value);
	}

	/**
	 * 選択肢をセットする.
	 * 
	 * @access public
	 * @param array $options 選択肢
	 * @return Pengin_Form_Property_Checkbox
	 */
	public function options(array $options)
	{
		$this->options = $options;
		return $this;
	}

	/**
	 * 選択肢を返す.
	 * 
	 * @access public
	 * @return array 選択肢
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * 選択肢を翻訳して返す.
	 * 
	 * @access public
	 * @return array 選択肢
	 */
	public function getOptionsLocal()
	{
		$options = array();

		foreach ( $this->options as $key => $value ) {
			$options[$key] = t($value);
		}

		return $options;
	}

	/**
	 * バリデーションを実行する.
	 * 
	 * @access public
	 * @return Pengin_Form_Property_Checkbox
	 */
	public function validate()
	{
		parent::validate();
		$this->validateOptions();
	}

	/**
	 * 選択肢についてのバリデーション.
	 * 
	 * @access public
	 * @return Pengin_Form_Property_Checkbox
	 */
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
		return array('Pengin_View_Html_Checkbox', 'render');
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
			'type'    => 'checkbox',
			'name'    => $this->name,
			'checked' => $this->value,
			'options' => $this->getOptionsLocal(),
		);

		$values = array_merge($values, $this->attributes);

		return $values;
	}

	/**
	 * 必須テストの合否を返す.
	 * 
	 * @access protected
	 * @param mixed $value
	 * @return bool
	 */
	protected function _testRequired($value)
	{
		return ( is_array($value) === true and empty($value) === false );
	}

	/**
	 * 正しい選択しかの合否を返す.
	 * 
	 * @access protected
	 * @param mixed $value
	 * @param array $options
	 * @return bool
	 */
	protected function _testInOptions($value, array $options)
	{
		if ( is_array($value) === false ) {
			return true;
		}

		$options = array_keys($options);

		foreach ( $value as $_value ) {
			if ( in_array($_value, $options) === false ) {
				return false;
			}
		}

		return $options;
	}
}
