<?php
class Profile_Model_Config extends Pengin_Model_AbstractModel
{
	public function __construct()
	{
		$this->val('id', self::INTEGER, null);
		$this->val('name', self::STRING, null, 255);
		$this->val('value', self::TEXT, null);
	}
}
