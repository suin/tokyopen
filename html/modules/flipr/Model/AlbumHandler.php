<?php
class Flipr_Model_AlbumHandler extends Pengin_Model_AbstractHandler
{
	public function albumExists($albumId)
	{
		$criteria = new Pengin_Criteria;
		$criteria->add('id', $albumId);
		$total = $this->count($criteria);
		return ( $total > 0 );
	}
}
