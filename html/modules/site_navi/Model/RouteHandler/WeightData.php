<?php

class SiteNavi_Model_RouteHandler_WeightData
{
	public $id;
	public $idPath;
	public $weight;
	public $weightPath;
	public $newWeight;
	public $newWeightPath;

	/**
	 * @static
	 * @param SiteNavi_Model_Route $route
	 * @return SiteNavi_Model_RouteHandler_WeightData
	 */
	public static function createFromRoute(SiteNavi_Model_Route $route)
	{
		$weightData = new self();
		$weightData->id = $route->get('id');
		$weightData->idPath = $route->get('id_path');
		$weightData->weight = $route->get('weight');
		$weightData->weightPath = $route->get('weight_path');
		return $weightData;
	}

	/**
	 * @param int $weight
	 */
	public function setNewWeight($weight)
	{
		$this->newWeight = $weight;
		$this->newWeightPath = $this->_getNewWeightPath($this->weightPath, $this->newWeight);
	}

	/**
	 * @param string $weightPath
	 * @param int $newWeight
	 * @return string
	 */
	protected function _getNewWeightPath($weightPath, $newWeight)
	{
		return sprintf('%s/%03d/', dirname($weightPath), $newWeight);
	}
}
