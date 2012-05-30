<?php
class Profile_Model_SetPropertyLink extends Pengin_Model_AbstractModel
{
	public function __construct()
	{
		$this->val('set_id', self::INTEGER, null, 11);
		$this->val('property_id', self::INTEGER, null, 11);
	}
}
