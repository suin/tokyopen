<?php

class SiteNavi_Form_AdminPageMove extends Pengin_Form
{
	public function setUpForm()
	{
		$this->title(t("Edit Move"));
		$this->name('SiteNavi_Form_AdminPageMove');
	}

	public function setUpProperties()
	{
		$this
			->add('source_id', 'Number')
			->label("Source ID")
			->required()
			->min(1);

		$this
			->add('position', 'Radio')
			->label("Position")
			->required()
			->options(array(
				'before' => "Before",
				'after'  => "After",
				'into'   => "Into",
		));

		$this
			->add('target_id', 'Number')
			->label("Target ID")
			->required()
			->min(1);
	}

	public function validateSourceId(Pengin_Form_Property $property)
	{
		$id = $property->getValue();
		$handler = $this->_getRouteHandler();

		if ( $handler->exists($id) === false ) {
			$this->addError(t("Data not found for ID: {1}", $id));
		}
	}

	public function validateTargetId(Pengin_Form_Property $property)
	{
		$id = $property->getValue();
		$handler = $this->_getRouteHandler();

		if ( $handler->exists($id) === false ) {
			$this->addError(t("Data not found for ID: {1}", $id));
		}
	}

	/**
	 * @return SiteNavi_Model_RouteHandler
	 */
	protected function _getRouteHandler()
	{
		$pengin = Pengin::getInstance();
		return $pengin->getModelHandler('Route');
	}
}
