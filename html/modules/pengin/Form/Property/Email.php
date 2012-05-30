<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_Email extends Pengin_Form_Property_Text
{
	public function validate()
	{
		parent::validate();
		$this->_validateEmail();
	}

	protected function _validateEmail()
	{
		$value = $this->getValue();
		if($value == ''){
			return;
		}

		if( Pengin_Validator::email($value) === false ){
			$this->addError(t('{1} is Invalid Email format.',$this->getLabel()));
			return;
		}
	}
}
