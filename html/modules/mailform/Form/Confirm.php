<?php
class Mailform_Form_Confirm extends Pengin_Form
{
	protected $propertyNamespaces = array(
		'Mailform_Plugin_Vendor_',
		'Mailform_Plugin_Core_',
	);

	protected $fieldModels = array();

	public function __construct(array $fieldModels)
	{
		$this->fieldModels = $fieldModels;
		parent::__construct();
	}

	public function setUpProperties()
	{
		foreach ( $this->fieldModels as $fieldModel ) {
			$name        = $fieldModel->get('name');
			$type        = $fieldModel->get('type');
			$label       = $fieldModel->get('label');
			$description = $fieldModel->get('description');
			$required    = $fieldModel->get('required');
			$options     = $fieldModel->get('options');

			$property = $this->add($name, $type);
			$property->label($label);
			$property->description($description);

			if ( $required == 1 ) {
				$property->required();
			}

			if ( isset($options['value']) === true ) {
				$property->value($options['value']);
			}

			$property->applyPluginOptions($options); // 各プロパティ(フィールド)独自のオプションは、各プロパティのクラスでセットアップする
		}
	}
}