<?php
class Nano_Model_Contents extends Pengin_Model_AbstractModel
{
	public function __construct()
	{
		$this->val('id', self::INTEGER);
		$this->val('title', self::STRING, '', 255);
		$this->val('content', self::TEXT, null);
		$this->val('created', self::DATETIME, null);
		$this->val('creator_id', self::INTEGER, null, 11);
		$this->val('modified', self::DATETIME, null);
		$this->val('modifier_id', self::INTEGER, null, 11);
	}
}
