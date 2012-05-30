<?php
class NiceBlock_Model_Block extends Pengin_Model_AbstractModel
{
	const SIDE_LEFT          = 0;
	const SIDE_RIGHT         = 1;
	const SIDE_BOTH          = 2;
	const SIDE_CENTER_LEFT   = 3;
	const SIDE_CENTER_RIGHT  = 4;
	const SIDE_CENTER_CENTER = 5;
	const SIDE_CENTER_ALL    = 6;

	const VISIBLE   = 1;
	const INVISIBLE = 0;

	const BLOCK_TYPE_MODULE = 'M';
	const BLOCK_TYPE_CUSTOM = 'C';

	const CUSTOM_TYPE_HTML   = 'H';
	const CUSTOM_TYPE_PHP    = 'P';
	const CUSTOM_TYPE_SMILES = 'S';
	const CUSTOM_TYPE_TEXT   = 'T';

	const ACTIVE   = 1;
	const INACTIVE = 0;

	public function __construct()
	{
		$this->val('bid', self::INTEGER);
		$this->val('mid', self::INTEGER);
		$this->val('func_num', self::INTEGER);
		$this->val('options', self::STRING, '', 255);
		$this->val('name', self::STRING, '', 150);
		$this->val('title', self::STRING, '', 255);
		$this->val('content', self::TEXT);
		$this->val('side', self::INTEGER, 0);
		$this->val('weight', self::INTEGER, 0);
		$this->val('visible', self::INTEGER, 0);
		$this->val('block_type', self::STRING, '', 1);
		$this->val('c_type', self::STRING, '', 1);
		$this->val('isactive', self::INTEGER, 0);
		$this->val('dirname', self::STRING, '', 50);
		$this->val('func_file', self::STRING, '', 50);
		$this->val('show_func', self::STRING, '', 50);
		$this->val('edit_func', self::STRING, '', 50);
		$this->val('template', self::STRING, '', 50);
		$this->val('bcachetime', self::INTEGER, 0);
		$this->val('last_modified', self::INTEGER, 0);
	}
}
