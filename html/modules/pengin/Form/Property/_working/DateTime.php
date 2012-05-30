<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_DateTime extends Pengin_Form_Property
                                    implements Pengin_Form_Property_RangeInterface,
                                               Pengin_Form_Property_StepInterface,
                                               Pengin_Form_Property_PatternInterface
{
	protected $min  = null;
	protected $max  = null;
	protected $step = null;
	protected $pattern  = null;

	public function min($min)
	{
		$this->min = $min;
		return $this;
	}

	public function getMin()
	{
		return $this->min;
	}

	public function max($max)
	{
		$this->max = $max;
		return $this;
	}

	public function getMax()
	{
		return $this->max;
	}

	public function step($step)
	{
		$this->step = $step;
		return $this;
	}

	public function getStep()
	{
		return $this->step;
	}

	public function pattern($pattern)
	{
		$this->pattern = $pattern;
		return $this;
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	public function validate()
	{
		parent::validate();
		$this->validateRange();
		$this->validateStep();
		$this->validatePattern();
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

	public function validatePattern()
	{
		if ( $this->pattern !== null )
		{
			if ( $this->_matchesPattern($this->value, $this->pattern) === false )
			{
				$this->addError("Invalid format.");
			}
		}

		return $this;
	}

	protected function _validateMax()
	{
		if ( $this->max !== null )
		{
			if ( $this->_isLargerThan($this->value, $this->max) === true )
			{
				$this->addError("Must be smaller than {1}"); // TODO
			}
		}
	}

	protected function _validateMin()
	{
		if ( $this->min !== null )
		{
			if ( $this->_isSmallerThan($this->value, $this->min) === true )
			{
				$this->addError("Must be larger than {1}"); // TODO
			}
		}
	}

	protected function _isInStep($value, $step)
	{
		return ( $value % $step === 0 );
	}

	protected function _isLargerThan($value, $max)
	{
		return ( $value > $max );
	}

	protected function _isSmallerThan($value, $min)
	{
		return ( $value < $min );
	}

	protected function _matchesPattern($string, $pattern)
	{
		return ( preg_match($pattern, $string) > 0 );
	}
}
