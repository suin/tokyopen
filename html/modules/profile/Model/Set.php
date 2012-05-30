<?php
class Profile_Model_Set extends Pengin_Model_AbstractModel
{
	public function __construct()
	{
		$this->val('id', self::INTEGER, null, 11);
		$this->val('name', self::STRING, null, 255);
		$this->val('title', self::STRING, null, 255);
		$this->val('description', self::TEXT, null);
		$this->val('created', self::DATETIME, null);
		$this->val('creator_id', self::INTEGER, null, 8);
		$this->val('modified', self::DATETIME, null);
		$this->val('modifier_id', self::INTEGER, null, 8);
	}
}
