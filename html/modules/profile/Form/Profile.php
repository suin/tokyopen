<?php
class Profile_Form_Profile extends Pengin_Form
{
	protected $propertyNamespaces = array(
		'Profile_Plugin_Vendor_',
		'Profile_Plugin_Core_',
	);

	protected $setId = null;

	public function __construct($setId)
	{
		$this->setId = $setId;
		parent::__construct();
	}

	public function setUpForm()
	{
		$setModel = Pengin::getInstance()->getModelHandler('Set')->load($this->setId);
		$this->title($setModel->get('title'));
	}

	public function setUpProperties()
	{
		$propertyModels = Pengin::getInstance()->getModelHandler('Property')->findBySetId($this->setId);

		foreach ( $propertyModels as $propertyModel ) {
			$name     = $propertyModel->get('name');
			$label    = $propertyModel->get('label');
			$note     = $propertyModel->get('note');
			$required = $propertyModel->isRequired();
			$type     = $propertyModel->get('type');
			$options  = $propertyModel->getOptions();

			$property = $this->add($name, $type);
			$property->label($label);

			if ( $required ) {
				$property->required();
			}

			if ( $note ) {
				$property->description($note);
			}

			if ( is_array($options) === true and $property instanceof Pengin_Form_Property_OptionInterface ) {
			
				if ( $required == false and $property instanceof Profile_Plugin_Core_Select ) {
					$options = array('' => '----') + $options;
				}
			
				$property->options($options);
			}

			if ( $property instanceof Profile_Plugin_Core_Textarea ) {
				$property->attr('cols', 50);
				$property->attr('rows', 10); // TODO >> 設定で変更できるようにする
			}
		}
	}

	/**
	 * 入力値をモデルに反映する.
	 * 
	 * @access public
	 * @param Profile_Model_User $model
	 * @return Pengin_Form
	 */
	public function updateModel(Profile_Model_User $model)
	{
		$pluginManager = new Profile_Plugin_Manager();
	
		foreach ( $this->properties as $property ) {

			if ( $pluginManager->call($property, 'hasUpdateModel') === true ) {
				$property->updateModel($model);
			} else {
				$name  = $property->getName();
				$value = $property->exportValue();
				$model->setVar($name, $value);
			}
		}
		return $this;
	}
}
