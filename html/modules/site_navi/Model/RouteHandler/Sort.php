<?php

/**
 * SiteNavi_Model_RouteHandler::sort() メソッドを担当
 */
class SiteNavi_Model_RouteHandler_Sort
{
	const POSITION_BEFORE = 'before';
	const POSITION_AFTER  = 'after';

	/** @var SiteNavi_Model_RouteHandler */
	protected $handler = null;
	/** @var XoopsMySQLDatabaseSafe */
	protected $db = null;
	/** @var SiteNavi_Model_RouteHandler_WeightDataManager */
	protected $weightDataManager = null;
	protected $table = '';

	/** @var SiteNavi_Model_Route */
	protected $source = null;
	/** @var SiteNavi_Model_Route */
	protected $target = null;
	protected $position = '';

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

	/**
	 * @param SiteNavi_Model_Route $source
	 * @param SiteNavi_Model_Route $target
	 * @param string $position
	 * @throws Exception
	 */
	public function execute(SiteNavi_Model_Route $source, SiteNavi_Model_Route $target, $position)
	{
		$this->source = $source;
		$this->target = $target;
		$this->position = $position;

		try {
			$this->_validate();
			$this->_sort();
		} catch ( Exception $e ) {
			throw $e;
		}
	}

	/**
	 * @throws RuntimeException
	 */
	protected function _validate()
	{
		if ( in_array($this->position, array(self::POSITION_BEFORE, self::POSITION_AFTER)) === false ) {
			throw new RuntimeException('Unexpected position was given: '.$this->position);
		}

		if ( $this->handler->isInSameFolder($this->source, $this->target) === false ) {
			throw new RuntimeException('Source and target is not in the same folder.');
		}
	}

	/**
	 * @throws Exception
	 */
	protected function _sort()
	{
		try {
			$sourceId = $this->source->get('id');
			$targetId = $this->target->get('id');

			$weightDataArray = $this->weightDataManager->getWeightDataArray($this->source->get('parent_id'));

			if ( $this->position === self::POSITION_BEFORE ) {
				$weightDataArray->addBefore($targetId, $weightDataArray[$sourceId]);
			} else {
				$weightDataArray->addAfter($targetId, $weightDataArray[$sourceId]);
			}

			$this->weightDataManager->updateWeightDataArrayRecursively($weightDataArray);
		} catch ( Exception $e ) {
			throw $e;
		}

		$this->_refreshModel();
	}

	protected function _refreshModel()
	{
		/** @var SiteNavi_Model_Route $newPage */
		/** @var SiteNavi_Model_Route $newFolder */
		$newSource = $this->handler->load($this->source->get('id'));
		$newTarget = $this->handler->load($this->target->get('id'));
		$this->source->setVars($newSource->getVars());
		$this->target->setVars($newTarget->getVars());
	}
}
