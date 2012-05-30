<?php
class Profile_Model_SetHandler extends Pengin_Model_AbstractHandler
{
	public function exists($id)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add($this->primary, $id);
		$total = $this->count($criteria);
		return ( $total > 0 );
	}

	public function existsName($name)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('name', $name);
		$total = $this->count($criteria);
		return ( $total > 0 );
	}

	/**
	 * titleをすべて返す.
	 * 
	 * @access public
	 * @return array
	 */
	public function getTitles()
	{
		$titles = array();

		$query  = "SELECT id, title FROM %s";
		$query  = sprintf($query, $this->table);
		$result = $this->_query($query);

		while ( $row = $this->db->fetchArray($result) ) {
			$id    = $row['id'];
			$title = $row['title'];
			$titles[$id] = $title;
		}

		return $titles;
	}
}
