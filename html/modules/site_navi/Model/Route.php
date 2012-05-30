<?php
class SiteNavi_Model_Route extends Pengin_Model_AbstractModel
{
	const TYPE_PAGE          = 0;
	const TYPE_EXTERNAL_LINK = 1;
	const TYPE_MODULE        = 2;

	protected static $typeNames = array(
		self::TYPE_PAGE          => 'page',
		self::TYPE_EXTERNAL_LINK => 'externalLink',
		self::TYPE_MODULE        => 'module',
	);

	public function __construct()
	{
		$this->val('id', self::INTEGER, null, 11);
		$this->val('parent_id', self::INTEGER, null, 11);
		$this->val('id_path', self::STRING, null, 255);
		$this->val('title', self::STRING, null, 255);
		$this->val('weight', self::INTEGER, null, 3);
		$this->val('weight_path', self::STRING, null, 255);
		$this->val('level', self::INTEGER, null, 5);
		$this->val('children', self::INTEGER, 0, 5);
		$this->val('type', self::INTEGER, 0, 1);
		$this->val('module_id', self::INTEGER, null, 5);
		$this->val('content_id', self::STRING, null, 255);
		$this->val('url', self::TEXT, null);
		$this->val('private_flag', self::INTEGER, 0, 1);
		$this->val('invisible_in_menu_flag', self::INTEGER, 0, 1);
		$this->val('created', self::DATETIME, null);
		$this->val('creator_id', self::INTEGER, null, 11);
		$this->val('modified', self::DATETIME, null);
		$this->val('modifier_id', self::INTEGER, null, 11);
	}

	public function hasChildren()
	{
		return ( $this->get('children') > 0 );
	}

	public function getTypeName()
	{
		$type = $this->get('type');
		return self::$typeNames[$type];
	}

	public function getIconName()
	{
		if ( $this->hasChildren() === true ) {
			$name = 'Folder';
		} else {
			$name = ucfirst($this->getTypeName());
		}
		
		return 'icon'.$name;
	}

	public function getVars()
	{
		$vars = parent::getVars();
		$vars['type_name'] = $this->getTypeName();
		$vars['icon_name'] = $this->getIconName();
		return $vars;
	}

	public function toJson()
	{
		$vars = $this->getVars();
		return json_encode($vars);
	}
}
