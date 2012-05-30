<?php
class Footer_Model_Menu extends Pengin_Model_AbstractModel
{
	public function __construct()
	{
		$this->val('id', self::INTEGER, null, 11);
		$this->val('title', self::STRING, null, 255);
		$this->val('url', self::STRING, null, 255);
		$this->val('weight', self::INTEGER, null, 5);
		$this->val('created', self::DATETIME, null);
		$this->val('modified', self::DATETIME, null);
		$this->val('creator_id', self::INTEGER, null, 10);
		$this->val('modifier_id', self::INTEGER, null, 11);
	}
}
