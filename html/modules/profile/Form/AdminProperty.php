<?php
class Profile_Form_AdminProperty extends Pengin_Form
{
	public function setUpForm()
	{
		$this->title("Edit Property");
	}

	public function setUpProperties()
	{
		$this->add('name', 'Text')
			->label("Name")
			->required()
			->longest(100)
			->pattern('/^[a-z][a-z0-9_]*$/')
			->description("Lower case alphabet and numbers");

		$this->add('label', 'Text')
			->label("Label")
			->required()
			->longest(256);

		$this->add('type', 'Select')
			->label("Type")
			->required()
			->options($this->_getTypeOptions());

		$this->add('required', 'RadioYesNo')
			->label("Required");

		$this->add('option', 'Textarea')
			->label("Option")
			->description("When you choose 'select', 'radio' or 'checkbox' type, please input options with separating line break.")
			->attr('rows', '5')
			->attr('cols', '50');

		$this->add('note', 'Textarea')
			->label("Description")
			->attr('rows', '5')
			->attr('cols', '50');

		$this->add('sets', 'Checkbox')
			->label("Use in pages")
			->required()
			->value(array_keys($this->_getSetOptions()))
			->options($this->_getSetOptions());
	}

	/**
	 * 名前の重複チェック.
	 * 
	 * @access public
	 * @return void
	 */
	public function validateName(Pengin_Form_Property $property)
	{
		if ( $property->getValue() == '' ) {
			return;
		}

		$this->_validateNameColumnOverlap($property);
		$this->_validateNameUniqueness($property);
	}

	/**
	 * setsのデフォルト値をデータベースのリンク情報をもとにセットする.
	 * 
	 * @access public
	 * @param integer $propertyId
	 * @return void
	 */
	public function useDataForSets($propertyId)
	{
		$setIds = Pengin::getInstance()->getModelHandler('SetPropertyLink')->getSetIds($propertyId);
		$this->property('sets')->value($setIds);
	}

	/**
	 * タイプの選択肢を返す.
	 * 
	 * @access protected
	 * @return array タイプの選択肢
	 */
	protected function _getTypeOptions()
	{
		$pluginManger = new Profile_Plugin_Manager();
		return $pluginManger->getLabels();
	}

	/**
	 * 表示する画面の選択肢を返す.
	 * 
	 * @access protected
	 * @return array 選択肢
	 */
	protected function _getSetOptions()
	{
		static $options = null;

		if ( $options === null ) {
			$options = Pengin::getInstance()->getModelHandler('Set')->getTitles();
		}

		return $options;
	}

	/**
	 * nameがusersテーブルのカラムと重複していないかチェックする.
	 * 
	 * @access protected
	 * @param Pengin_Form_Property $property
	 * @return void
	 */
	protected function _validateNameColumnOverlap(Pengin_Form_Property $property)
	{
		$label  = $property->getLabel();
		$value  = $property->getValue();
		$exists = Pengin::getInstance()->getModelHandler('User')->existsProperty($value);

		if ( $exists === true ) {
			$property->addError(t("{1} is unavailable. '{2}' column already exists in users-table.", $label, $value));
		}
	}

	/**
	 * nameがユニークかチェックする.
	 * 
	 * @access protected
	 * @param Pengin_Form_Property $property
	 * @return void
	 */
	protected function _validateNameUniqueness(Pengin_Form_Property $property)
	{
		$label  = $property->getLabel();
		$value  = $property->getValue();
		$exists = Pengin::getInstance()->getModelHandler('Property')->existsName($value);

		if ( $exists === true ) {
			$property->addError(t("{1} is duplicated. '{2}' has been already used.", $label, $value));
		}
	}
}
