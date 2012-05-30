<?php
class NiceBlock_Model_BlockHandler extends Pengin_Model_AbstractHandler
{
	protected $object  = 'NiceBlock_Model_Block';
	protected $table   = 'newblocks';
	protected $primary = 'bid';

	public function __construct()
	{
		$table = $this->table;
		parent::__construct();
		$this->table = $this->db->prefix($table);
	}
}
