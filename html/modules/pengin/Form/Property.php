<?php
/**
 * {header_doc}
 */

abstract class Pengin_Form_Property
{
	protected $name        = ''; // フィールド名
	protected $label       = ''; // 表示名
	protected $description = ''; // 説明文
	protected $type        = null; // タイプ
	protected $value       = null; // 入力値
	protected $required    = false; // 必須フラグ
	protected $errors      = array(); // エラーメッセージ
	protected $attributes  = array(); // 属性

	/**
	 * フィールド名をセットする.
	 * 
	 * @access public
	 * @param string $name
	 * @return Pengin_Form_Property
	 */
	public function name($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * フィールド名を返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * フィールド表示名をセットする.
	 * 
	 * @access public
	 * @param string $label
	 * @return Pengin_Form_Property
	 */
	public function label($label)
	{
		$this->label = $label;
		return $this;
	}

	/**
	 * フィールド表示名を返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * フィールド表示名を翻訳して返す.
	 * 
	 * @return string
	 */
	public function getLabelLocal()
	{
		return t($this->label);
	}

	/**
	 * 説明文をセットする.
	 * 
	 * @access public
	 * @param string $description
	 * @return Pengin_Form_Property
	 */
	public function description($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * 説明文を返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * 説明文を翻訳して返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function getDescriptionLocal()
	{
		return t($this->description);
	}

	/**
	 * 入力値をセットする.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return Pengin_Form_Property
	 */
	public function value($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * 入力値を返す.
	 * 
	 * @access public
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * 値を人が分かるような形で表現する.
	 * 
	 * @access public
	 * @return string
	 */
	public function describeValue()
	{
		return $this->value;
	}

	/**
	 * describeValue()の結果を翻訳して返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function describeValueLocal()
	{
		return $this->describeValue();
	}

	/**
	 * 入力値を保存可能な書式で返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function exportValue()
	{
		return $this->value;
	}

	/**
	 * 保存可能な書式で入力値をセットする.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return Pengin_Form_Property
	 */
	public function importValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * 必須フラグをセットする.
	 * 
	 * @access public
	 * @return Pengin_Form_Property
	 */
	public function required()
	{
		$this->required = true;
		return $this;
	}

	/**
	 * 必須フラグをアンセットする.
	 * 
	 * @access public
	 * @return Pengin_Form_Property
	 */
	public function unrequired()
	{
		$this->required = false;
		return $this;
	}

	/**
	 * 必須フラグを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public function isRequired()
	{
		return $this->required;
	}

	/**
	 * バリデーションを実行する.
	 * 
	 * @access public
	 * @return Pengin_Form_Property
	 */
	public function validate()
	{
		if ( $this->required === true )
		{
			$this->validateRequired();
		}

		return $this;
	}

	/**
	 * エラーメッセージを追加する.
	 * 
	 * @access public
	 * @param string $message
	 * @return Pengin_Form_Property
	 */
	public function addError($message)
	{
		$this->errors[] = $message;
		return $this;
	}

	/**
	 * エラーメッセージを返す.
	 * 
	 * @access public
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * エラーがあるかを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public function hasError()
	{
		return ( count($this->errors) > 0 );
	}

	/**
	 * エラーメッセージをクリアする.
	 * 
	 * @access public
	 * @return Pengin_Form_Property
	 */
	public function clearErrors()
	{
		$this->errors = array();
		// TODO >> すべてのプロパティのエラーメッセージもクリアする
		return $this;
	}

	/**
	 * 必須についてバリデーションを行う.
	 * 
	 * @access public
	 * @return Pengin_Form_Property
	 */
	public function validateRequired()
	{
		if ( $this->_testRequired($this->value) === false ) {
			$this->addError(t("Please enter {1}.", $this->getLabelLocal()));
		}

		return $this;
	}

	/**
	 * 属性をセットする.
	 * 
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @return Pengin_Form_Property
	 */
	public function attr($name, $value)
	{
		if ( isset($this->$name) === true ) {
			// クラスメンバ変数と同じ属性は指定できない
			throw new RuntimeException('Can\'t duplicate attribute: '.$name);
		}

		$this->attributes[$name] = $value;
		return $this;
	}

	/**
	 * 属性を返す.
	 * 
	 * @access public
	 * @param string $name
	 * @return string
	 */
	public function getAttr($name)
	{
		return $this->attributes[$name];
	}

	/**
	 * 属性を削除する.
	 * 
	 * @access public
	 * @param string $name
	 * @return Pengin_Form_Property
	 */
	public function removeAttr($name)
	{
		unset($this->attributes[$name]);
		return $this;
	}

	/**
	 * 属性をすべて返す.
	 * 
	 * @access public
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * 配列に変換する.
	 * 
	 * @access public
	 * @return array
	 */
	public function toArray()
	{
		$values = array();

		foreach ( $this as $name => $value ) {
			$values[$name] = $value;
		}

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
		return ( strval($value) != '' );
	}
}
