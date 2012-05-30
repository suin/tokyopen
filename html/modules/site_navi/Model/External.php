<?php
class SiteNavi_Model_External extends Pengin_Model_AbstractModel
{
	public function __construct()
	{
		$this->val('id', self::INTEGER, null, 11);
		$this->val('title', self::STRING, null, 255);
		$this->val('url', self::STRING, null, 255);
		$this->val('created', self::DATETIME, null);
		$this->val('creator_id', self::INTEGER, null, 11);
		$this->val('modified', self::DATETIME, null);
		$this->val('modifier_id', self::INTEGER, null, 11);
	}
}
