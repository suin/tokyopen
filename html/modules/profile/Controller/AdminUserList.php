<?php
class Profile_Controller_AdminUserList extends Profile_Abstract_AdminController
{
	protected $useModels = array('User');

	protected $start = 0;
	protected $limit = 50;
	protected $total = 0;
	protected $sort  = 'uid';
	protected $order = 'ASC';

	public function __construct()
	{
		parent::__construct();
		$this->pageTitle = t("User list");
	}

	protected function _defaultAction()
	{
		$this->start = (int) $this->get('start');
		$this->total = $this->_getTotal();
		$this->output['users'] = $this->userHandler->find(null, $this->sort, $this->order, $this->limit, $this->start);

		$pager = $this->_getPager();
		$this->output['pages'] = $pager->getPages();
		$this->_view();
	}

	protected function _getTotal()
	{
		return $this->userHandler->count();
	}

	protected function _getPager()
	{
		$pager = new Pengin_Pager(array(
			'current' => $this->start, 
			'perPage' => $this->limit, 
			'total'   => $this->total, 
		));

		return $pager;
	}
}
