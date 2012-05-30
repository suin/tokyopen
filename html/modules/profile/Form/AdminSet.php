<?php
class Profile_Form_AdminSet extends Pengin_Form
{
	public function setUpForm()
	{
		$this->title("Add Property Set");
	}

	public function setUpProperties()
	{
		$this->add('name', 'Text')
			->label("Name")
			->required()
			->longest(256)
			->pattern('/^[a-z][a-z0-9_]*$/')
			->description("Lower case alphabet and numbers");

		$this->add('title', 'Text')
			->label("Title")
			->required()
			->longest(256);

		$this->add('description', 'Textarea')
			->label("Description")
			->attr('rows', '5')
			->attr('cols', '50');
	}

	/**
	 * 名前の重複チェック.
	 * 
	 * @access public
	 * @return void
	 */
	public function validateName(Pengin_Form_Property $property)
	{
		$label  = $property->getLabel();
		$value  = $property->getValue();

		if ( $value == '' ) {
			return;
		}

		$exists = Pengin::getInstance()->getModelHandler('Set')->existsName($value);

		if ( $exists === true ) {
			$property->addError(t("{1} is duplicated. '{2}' has been already used.", $label, $value));
		}
	}
}
