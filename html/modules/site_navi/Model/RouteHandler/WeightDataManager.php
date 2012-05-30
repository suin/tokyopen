<?php

/**
 * WeightData マネージャ
 */
class SiteNavi_Model_RouteHandler_WeightDataManager
{
	/** @var XoopsMySQLDatabaseSafe */
	protected $db;
	protected $table;

	/**
	 * @param XoopsDatabase $db
	 * @param string $table
	 */
	public function __construct(XoopsDatabase $db, $table)
	{
		$this->db = $db;
		$this->table = $table;
	}

	/**
	 * フォルダ内での次の weight 値(weight最大値 + 1)を返す
	 * @param int $parentId
	 * @return int
	 */
	public function getNextWeightInFolder($parentId)
	{
		$bottomPageWeight = $this->_getBottomPageWeight($parentId);

		if ( $bottomPageWeight === false ) {
			$newWeight = 1; // ページがなければ1番上になるので1を返す
		} else {
			$newWeight = $bottomPageWeight + 1;
		}

		return $newWeight;
	}

	/**
	 * parent_id からそのフォルダにあるページの並び順情報を返す
	 * @param int $parentId
	 * @return SiteNavi_Model_RouteHandler_WeightDataArray
	 */
	public function getWeightDataArray($parentId)
	{
		$weightDataArray = new SiteNavi_Model_RouteHandler_WeightDataArray();
		$query = "SELECT id, id_path, weight, weight_path FROM %s WHERE parent_id = %u ORDER BY weight ASC";
		$query = sprintf($query, $this->table, $parentId);
		$result = $this->db->query($query);

		while ( $row = $this->db->fetchArray($result) ) {
			$weightData = new SiteNavi_Model_RouteHandler_WeightData();
			$weightData->id         = $row['id'];
			$weightData->idPath     = $row['id_path'];
			$weightData->weight     = $row['weight'];
			$weightData->weightPath = $row['weight_path'];
			$weightDataArray[$weightData->id] = $weightData;
		}

		return $weightDataArray;
	}

	/**
	 * 並び順情報を子ページに渡って再帰的に更新する
	 * @param SiteNavi_Model_RouteHandler_WeightDataArray $weightDataArray
	 * @throws Exception
	 */
	public function updateWeightDataArrayRecursively(SiteNavi_Model_RouteHandler_WeightDataArray $weightDataArray)
	{
		$weight = 0;

		/** @var SiteNavi_Model_RouteHandler_WeightData $weightData */
		foreach ( $weightDataArray as $weightData ) {
			$weight += 1;

			if ( $weightData->weight == $weight ) {
				continue; // 今のweightと同じだったら更新する必要はない
			}

			try {
				$this->_updateWeight($weightData, $weight);
				$this->_replaceWeightPath($weightData);
			} catch ( Exception $e ) {
				throw $e;
			}
		}
	}

	/**
	 * @param SiteNavi_Model_RouteHandler_WeightData $data
	 * @param int $weight
	 * @throws RuntimeException
	 */
	protected function _updateWeight(SiteNavi_Model_RouteHandler_WeightData $data, $weight)
	{
		$query = "UPDATE %s SET weight = %u WHERE id = %u";
		$query = sprintf($query, $this->table, $weight, $data->id);

		if ( $this->db->query($query) === false ) {
			throw new RuntimeException('Failed to update `weight` value: ID: '.$data->id);
		}

		$data->setNewWeight($weight);
	}

	/**
	 * @param SiteNavi_Model_RouteHandler_WeightData $data
	 * @throws RuntimeException
	 */
	protected function _replaceWeightPath(SiteNavi_Model_RouteHandler_WeightData $data)
	{
		$query = "UPDATE %s SET weight_path = REPLACE(weight_path, '%s', '%s') WHERE id_path LIKE '%s%%'";
		$query = sprintf($query, $this->table, $data->weightPath, $data->newWeightPath, $data->idPath);

		if ( $this->db->query($query) === false ) {
			throw new RuntimeException('Failed to replace `weight_path`: id_path: '.$data->idPath);
		}
	}

	/**
	 * @param int $parentId
	 * @return int|bool
	 */
	protected function _getBottomPageWeight($parentId)
	{
		$query = "SELECT weight FROM %s WHERE parent_id = %u ORDER BY weight DESC LIMIT 1";
		$query = sprintf($query, $this->table, $parentId);
		$result = $this->db->query($query);
		$row = $this->db->fetchArray($result);

		if ( is_array($row) === false ) {
			return false;
		}

		return $row['weight'];
	}
}
