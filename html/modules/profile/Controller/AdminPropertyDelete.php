<?php
class Profile_Controller_AdminPropertyDelete extends Pengin_Controller_AbstractDeleteSimpleForm
{
	protected $useModels = array('Property', 'User');

	protected function _getModelHandler()
	{
		return $this->propertyHandler;
	}

	protected function _deleteData()
	{
		if ( $this->propertyHandler->delete($this->id) === false ) {
			throw new RuntimeException('Failed to delete.');
		}
	}

	protected function _afterCommit()
	{
		$this->userHandler->removeProperty($this->model->get('name'));
	}

	protected function _getReturnUri()
	{
		return $this->root->url('property_list');
	}
}
