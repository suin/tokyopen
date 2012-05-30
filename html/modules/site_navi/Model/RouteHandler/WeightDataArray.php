<?php

/**
 * WeightData コレクション
 */
class SiteNavi_Model_RouteHandler_WeightDataArray extends ArrayObject
{
	/**
	 * WeightData を前に追加する
	 * @param int $routeId
	 * @param SiteNavi_Model_RouteHandler_WeightData $newMember
	 */
	public function addBefore($routeId, SiteNavi_Model_RouteHandler_WeightData $newMember)
	{
		$newArray = array();

		if ( isset($this[$newMember->id]) === true ) {
			unset($this[$newMember->id]);
		}

		/** @var SiteNavi_Model_RouteHandler_WeightData $weightData */
		foreach ( $this as $weightData ) {
			if ( $weightData->id == $routeId ) {
				$newArray[$newMember->id] = $newMember;
			}

			$newArray[$weightData->id] = $weightData;
		}

		$this->exchangeArray($newArray);
	}

	/**
	 * WeightData を後に追加する
	 * @param $routeId
	 * @param SiteNavi_Model_RouteHandler_WeightData $newMember
	 */
	public function addAfter($routeId, SiteNavi_Model_RouteHandler_WeightData $newMember)
	{
		$newArray = array();

		if ( isset($this[$newMember->id]) === true ) {
			unset($this[$newMember->id]);
		}

		/** @var SiteNavi_Model_RouteHandler_WeightData $weightData */
		foreach ( $this as $weightData ) {
			$newArray[$weightData->id] = $weightData;

			if ( $weightData->id == $routeId ) {
				$newArray[$newMember->id] = $newMember;
			}
		}

		$this->exchangeArray($newArray);
	}
}
