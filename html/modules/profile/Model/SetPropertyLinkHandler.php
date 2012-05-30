<?php
class Profile_Model_SetPropertyLinkHandler extends Pengin_Model_AbstractHandler
{
	protected $primary = 'set_id';

	/**
	 * セットIDを取得する.
	 * 
	 * @access public
	 * @param integer $propertyId
	 * @return array セットID
	 */
	public function getSetIds($propertyId)
	{
		$setIds = array();

		$query = "SELECT set_id FROM `%s` WHERE property_id = %u";
		$query = sprintf($query, $this->table, $propertyId);
		$result = $this->_query($query);

		while ( list($setId) = $this->db->fetchRow($result) ) {
			$setIds[] = $setId;
		}

		return $setIds;
	}

	public function getPropertyIds($setId)
	{
		$propertyIds = array();

		$query = "SELECT property_id FROM `%s` WHERE set_id = %u";
		$query = sprintf($query, $this->table, $setId);
		$result = $this->_query($query);

		while ( list($propertyId) = $this->db->fetchRow($result) ) {
			$propertyIds[] = $propertyId;
		}

		return $propertyIds;
	}

	/**
	 * リンクを追加する.
	 * 
	 * @access public
	 * @param integer $propertyId
	 * @param integer $setId
	 * @return bool
	 */
	public function addLink($propertyId, $setId)
	{
		$model = $this->create();
		$model->set('set_id', $setId);
		$model->set('property_id', $propertyId);
		return $this->save($model);
	}

	/**
	 * リンクを追加する.
	 * 
	 * @access public
	 * @param integer $propertyId
	 * @param array $setIds
	 * @return bool
	 */
	public function addLinks($propertyId, array $setIds)
	{
		$result = $this->removeLink($propertyId);

		if ( $result === false ) {
			return false;
		}

		foreach ( $setIds as $setId ) {
			$result = $this->addLink($propertyId, $setId);

			if ( $result === false ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * リンクを削除する.
	 * 
	 * @access public
	 * @param integer $propertyId
	 * @param integer $setIds
	 * @return bool
	 */
	public function removeLink($propertyId, $setId = null)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('property_id', $propertyId);

		if ( $setId !== null ) {
			$criteria->add('set_id', $setId);
		}

		return $this->deleteAll($criteria);
	}
}
