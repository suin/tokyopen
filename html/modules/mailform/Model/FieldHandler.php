<?php
class Mailform_Model_FieldHandler extends Pengin_Model_AbstractHandler
{
	const DEFAULT_NAME_FORMAT = 'field_%u';

	public function findByFormId($formId)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('form_id', $formId);
		return $this->find($criteria, 'weight', 'ASC', null, null, true);
	}

	public function deleteAllByIds(array $ids)
	{
		if ( is_array($ids) === false or count($ids) === 0 ) {
			return true;
		}

		$criteria = new Pengin_Criteria();
		$criteria->add('id', 'IN', $ids);
		return $this->deleteAll($criteria);
	}

	public function autoUpdateName(Mailform_Model_Field $model)
	{
		$id = $model->get('id');

		if ( $model->get('name') != '' or $id == 0 ) {
			return true;
		}

		$name = sprintf(self::DEFAULT_NAME_FORMAT, $id);
		$model->set('name', $name);
		return $this->save($model);
	}
}
