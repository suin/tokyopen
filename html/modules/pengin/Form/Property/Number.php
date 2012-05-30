<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_Number extends Pengin_Form_Property
                                  implements Pengin_Form_Property_RangeInterface,
                                             Pengin_Form_Property_StepInterface,
                                             Pengin_Form_Property_HtmlInterface
{
	protected $type = 'text';
	protected $min  = null;
	protected $max  = null;
	protected $step = null;

	/**
	 * Set min
	 * @param integer|float $min Min
	 * @returns object $this
	 */
	public function min($min)
	{
		$this->min = $min;
		return $this;
	}

	/**
	 * Returns min
	 * @returns integer|float Min
	 */
	public function getMin()
	{
		return $this->min;
	}

	/**
	 * Set max
	 * @param integer|float $max Max
	 * @returns object $this
	 */
	public function max($max)
	{
		$this->max = $max;
		return $this;
	}

	/**
	 * Returns max
	 * @returns integer|float Max
	 */
	public function getMax()
	{
		return $this->max;
	}

	/**
	 * Set step
	 * @param integer|float $step Step
	 * @returns object $this
	 */
	public function step($step)
	{
		$this->step = $step;
		return $this;
	}

	/**
	 * Returns step
	 * @returns integer|float Step
	 */
	public function getStep()
	{
		return $this->step;
	}

	public function validate()
	{
		parent::validate();
		$this->validateRange();
		$this->validateStep();
	}

	public function validateRange()
	{
		$this->_validateMax();
		$this->_validateMin();
		return $this;
	}

	public function validateStep()
	{
		if ( $this->step !== null )
		{
			if ( $this->_isInStep($this->value, $this->step) === false )
			{
				$this->addError("Invalid input"); // TODO consider message
			}
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
			'type'  => $this->type,
			'name'  => $this->name,
			'value' => $this->value,
		);

		if ( $this->max !== null ) {
			$values['maxlength'] = strlen(strval($this->max));
		}

		$values = array_merge($values, $this->attributes);

		return $values;
	}

	protected function _validateMax()
	{
		if ( $this->max !== null )
		{
			if ( $this->_isLargerThan($this->value, $this->max) === true )
			{
				$this->addError(t("Must be smaller than {1}", $this->max));
			}
		}
	}

	protected function _validateMin()
	{
		if ( $this->min !== null )
		{
			if ( $this->_isSmallerThan($this->value, $this->min) === true )
			{
				$this->addError(t("Must be larger than {1}", $this->min));
			}
		}
	}

	protected function _isInStep($value, $step)
	{
		return ( intval($value / $step) == $value / $step );
	}

	protected function _isLargerThan($value, $max)
	{
		return ( $value > $max );
	}

	protected function _isSmallerThan($value, $min)
	{
		return ( $value < $min );
	}
}
