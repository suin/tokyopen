<?php

/**
 * SiteNavi_Model_RouteHandler::move() メソッドを担当
 */
class SiteNavi_Model_RouteHandler_Move
{
	/** @var SiteNavi_Model_RouteHandler */
	protected $handler = null;
	/** @var XoopsMySQLDatabaseSafe */
	protected $db = null;
	/** @var SiteNavi_Model_RouteHandler_WeightDataManager */
	protected $weightDataManager = null;
	protected $table = '';

	/** @var SiteNavi_Model_Route */
	protected $page = null;
	/** @var SiteNavi_Model_Route */
	protected $folder = null;
	/** @var SiteNavi_Model_Route */
	protected $originalFolder = null;

	/**
	 * @param SiteNavi_Model_RouteHandler $handler
	 * @param XoopsDatabase $db
	 * @param string $table
	 * @throws InvalidArgumentException
	 */
	public function __construct(SiteNavi_Model_RouteHandler $handler, XoopsDatabase $db, $table)
	{
		$this->handler = $handler;
		$this->db = $db;
		$this->weightDataManager = new SiteNavi_Model_RouteHandler_WeightDataManager($db, $table);
		$this->table = $table;
	}

	public function execute(SiteNavi_Model_Route $page, SiteNavi_Model_Route $folder)
	{
		$this->page = $page;
		$this->folder = $folder;

		try {
			$this->_setUpOriginalFolder();
			$this->_validate();
			$this->_move();
		} catch ( Exception $e ) {
			throw $e;
		}
	}

	protected function _setUpOriginalFolder()
	{
		$this->originalFolder = $this->handler->load($this->page->get('parent_id'));

		if ( is_object($this->originalFolder) === false ) {
			throw new RuntimeException('Folder route model not found: ID: '.$this->page->get('parent_id'));
		}
	}

	protected function _validate()
	{
		if ( $this->page->get('parent_id') == $this->folder->get('id') ) {
			throw new RuntimeException('Page is already in that folder.');
		}

		$pagePath   = $this->page->get('id_path');
		$folderPath = $this->folder->get('id_path');

		if ( strpos($folderPath, $pagePath) !== false ) {
			// 再帰的な移動になる場合
			// 例えば、 /1/2/ を /1/2/5/ に入れるようとしたとき。
			throw new RuntimeException('Can not move the page in to that folder.');
		}
	}

	protected function _move()
	{
		$this->_moveOutFromOldFolder();
		$this->_moveInToNewFolder();
		$this->_updateChildren();
		$this->_refreshModel();
	}

	/**
	 * @throws RuntimeException
	 */
	protected function _moveOutFromOldFolder()
	{
		$parentId = $this->page->get('parent_id');
		$pageId   = $this->page->get('id');

		try {
			$weightData = $this->weightDataManager->getWeightDataArray($parentId);
			unset($weightData[$pageId]); // ページは出ていくので取り除く
			$this->weightDataManager->updateWeightDataArrayRecursively($weightData);
		} catch ( Exception $e ) {
			throw $e;
		}
	}

	protected function _moveInToNewFolder()
	{
		$oldIdPath     = $this->page->get('id_path');
//		$oldWeight     = $this->page->get('weight');
		$oldWeightPath = $this->page->get('weight_path');
		$oldLevel      = $this->page->get('level');

		$newIdPath     = $this->folder->get('id_path').$this->page->get('id').'/';
		$newWeight     = $this->weightDataManager->getNextWeightInFolder($this->folder->get('id'));
		$newWeightPath = sprintf('%s%03d/', $this->folder->get('weight_path'), $newWeight);
		$newLevel      = $this->folder->get('level') + 1;

		$levelDiff = $newLevel - $oldLevel;

		$this->page->set('id_path', $newIdPath);
		$this->page->set('weight', $newWeight);
		$this->page->set('weight_path', $newWeightPath);
		$this->page->set('level', $newLevel);
		$this->page->set('parent_id', $this->folder->get('id'));

		if ( $this->handler->save($this->page) === false ) {
			throw new RuntimeException('Failed to update page: rout_id: ' . $this->page->get('id'));
		}

		$query = "UPDATE {$this->table} SET id_path = REPLACE(id_path, '{$oldIdPath}', '{$newIdPath}'), weight_path = REPLACE(weight_path, '{$oldWeightPath}', '{$newWeightPath}'), level = level + {$levelDiff} WHERE id_path LIKE '{$oldIdPath}%'";

		if ( $this->db->query($query) === false ) {
			throw new RuntimeException('Failed to update path info.');
		}
	}

	protected function _updateChildren()
	{
		if ( $this->handler->updateChildren($this->originalFolder->get('id')) === false ) {
			throw new RuntimeException('Failed to update children total: ID: '.$this->originalFolder->get('id'));
		}

		if ( $this->handler->updateChildren($this->folder->get('id')) === false ) {
			throw new RuntimeException('Failed to update children total: ID: '.$this->folder->get('id'));
		}
	}

	protected function _refreshModel()
	{
		/** @var SiteNavi_Model_Route $newPage */
		/** @var SiteNavi_Model_Route $newFolder */
		$newPage   = $this->handler->load($this->page->get('id'));
		$newFolder = $this->handler->load($this->folder->get('id'));
		$this->page->setVars($newPage->getVars());
		$this->folder->setVars($newFolder->getVars());
	}
}
