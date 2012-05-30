<?php
abstract class Pengin_Model_AbstractViewHandler extends Pengin_Model_AbstractHandler
{
	public function save(&$obj)
	{
		trigger_error("ViewHandler can't modify view table.", E_USER_ERROR);
		return false;
	}

	public function delete($id)
	{
		trigger_error("ViewHandler can't modify view table.", E_USER_ERROR);
		return false;
	}
}
