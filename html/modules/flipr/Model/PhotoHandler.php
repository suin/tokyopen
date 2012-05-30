<?php
class Flipr_Model_PhotoHandler extends Pengin_Model_AbstractHandler
{
	public function deleteByAlbumId($albumId)
	{
		$albumId = intval($albumId);

		$sql = "DELETE FROM `%s` WHERE `album_id` = '%u'";
		$sql = sprintf($sql, $this->table, $albumId);

		$result = $this->_query($sql);

		if ( $result === false )
		{
			return false;
		}

		return true;
	}

	public function findByAlbumId($albumId, $sort = null, $order = null, $limit = null, $start = null)
	{
		$criteria = new Pengin_Criteria;
		$criteria->add('album_id', $albumId);
		$models = $this->find($criteria, $sort, $order, $limit, $start);
		return $models;
	}
}
