<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_Text extends Pengin_Form_Property 
                                implements Pengin_Form_Property_PatternInterface,
                                           Pengin_Form_Property_LengthInterface,
                                           Pengin_Form_Property_LengthRangeInterface,
                                           Pengin_Form_Property_HtmlInterface
{
	protected $pattern  = null;
	protected $length   = null;
	protected $longest  = null;
	protected $shortest = null;

	public function pattern($pattern)
	{
		$this->pattern = $pattern;
		return $this;
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	public function length($length)
	{
		$this->length = $length;
		return $this;
	}

	public function getLength()
	{
		return $this->length;
	}

	public function longest($length)
	{
		$this->longest = $length;
		return $this;
	}

	public function getLongest()
	{
		return $this->longest;
	}

	public function shortest($length)
	{
		$this->shortest = $length;
		return $this;
	}

	public function getShortest()
	{
		return $this->shortest;
	}

	public function validate()
	{
		parent::validate();
		$this->validatePattern();
		$this->validateLength();
		$this->validateLengthRange();
	}

	public function validatePattern()
	{
		if ( $this->pattern !== null )
		{
			if ( $this->_testPattern($this->value, $this->pattern) === false )
			{
				$this->addError(t("Invalid format {1}.", $this->getLabelLocal()));
			}
		}

		return $this;
	}

	public function validateLength()
	{
		if ( $this->length !== null )
		{
			if ( $this->_testLength($this->value, $this->length) === false )
			{
				$this->addError(t("{1}: Length must be {2} characters.", $this->getLabelLocal(), $this->length));
			}
		}

		return $this;
	}

	public function validateLengthRange()
	{
		$this->_validateLongest();
		$this->_validateShortest();
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
		return array('Pengin_View_Html_Input', 'render');
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
			'type'  => 'text',
			'name'  => $this->name,
			'value' => $this->value,
		);

		if ( $this->longest !== null ) {
			$values['maxlength'] = $this->longest;
		}

		$values = array_merge($values, $this->attributes);

		return $values;
	}

	protected function _testPattern($string, $pattern)
	{
		return ( preg_match($pattern, $string) > 0 );
	}

	protected function _testLength($value, $length)
	{
		return ( mb_strlen($value) === $length );
	}

	protected function _validateLongest()
	{
		if ( $this->longest !== null )
		{
			if ( $this->_testLongest($this->value, $this->longest) === false )
			{
				$this->addError(t("{1}: Must be {2} characters or shorter.", $this->getLabelLocal(), $this->longest));
			}
		}
	}

	protected function _validateShortest()
	{
		if ( $this->shortest !== null )
		{
			if ( $this->_testShortest($this->value, $this->shortest) === false )
			{
				$this->addError(t("{1}: Must be {2} characters or longer.", $this->getLabelLocal(), $this->shortest));
			}
		}
	}

	protected function _testLongest($value, $length)
	{
		return ( mb_strlen($value) <= $length );
	}

	protected function _testShortest($value, $length)
	{
		return ( mb_strlen($value) >= $length );
	}
}
