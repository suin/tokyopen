<?php
class Mailform_Model_Form extends Pengin_Model_AbstractModel
{
	public function __construct()
	{
		$this->val('id', self::INTEGER, null, 11);
		$this->val('title', self::STRING, null, 255);
		$this->val('mail_to_sender', self::INTEGER, null, 1);
		$this->val('mail_to_receiver', self::INTEGER, null, 1);
		$this->val('receiver_email', self::TEXT, null);
		$this->val('header_description', self::TEXT, null);
		$this->val('options', self::TEXT, null);
		$this->val('created', self::DATETIME, null);
		$this->val('creator_id', self::INTEGER, null, 8);
		$this->val('modified', self::DATETIME, null);
		$this->val('modifier_id', self::INTEGER, null, 8);
	}
	
	public function getMailBodyTemplate()
	{
		
	}
}
