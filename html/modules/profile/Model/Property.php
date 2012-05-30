<?php
class Profile_Model_Property extends Pengin_Model_AbstractModel
{
	/**
	 * 必須・任意フラグ
	 */
	const REQURED  = 1;
	const OPTIONAL = 0;

	public function __construct()
	{
		$this->val('id', self::INTEGER, null, 11);
		$this->val('name', self::STRING, null, 255);
		$this->val('label', self::STRING, null, 255);
		$this->val('type', self::STRING, null);
		$this->val('required', self::INTEGER, null, 1);
		$this->val('option', self::TEXT, null);
		$this->val('note', self::TEXT, null);
		$this->val('created', self::DATETIME, null);
		$this->val('creator_id', self::INTEGER, null, 8);
		$this->val('modified', self::DATETIME, null);
		$this->val('modifier_id', self::INTEGER, null, 8);
	}

	/**
	 * 必須かを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public function isRequired()
	{
		return ( $this->get('required') == self::REQURED );
	}

	/**
	 * 必須フラグを文字列で返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function describeRequired()
	{
		if ( $this->isRequired() === true ) {
			return 'required';
		} else {
			return 'optional';
		}
	}

	/**
	 * getOptions function.
	 * 
	 * @access public
	 * @return array
	 */
	public function getOptions()
	{
		$pluginManager = new Profile_Plugin_Manager();
		return $pluginManager->getOptions($this->get('type'), $this->get('option'));
	}
}
