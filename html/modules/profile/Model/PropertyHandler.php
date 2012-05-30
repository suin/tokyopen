<?php
class Profile_Model_PropertyHandler extends Pengin_Model_AbstractHandler
{
	/**
	 * nameが存在しているかを返す.
	 * 
	 * @access public
	 * @param string $name
	 * @return bool
	 */
	public function existsName($name)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('name', $name);
		$total = $this->count($criteria);
		return ( $total > 0 );
	}

	/**
	 * findBySetId function.
	 * 
	 * @access public
	 * @param integer $setId
	 * @return array|false
	 */
	public function findBySetId($setId)
	{
		$ids = Pengin::getInstance()->getModelHandler('SetPropertyLink')->getPropertyIds($setId);

		if ( is_array($ids) === false or count($ids) === 0 ) {
			return array();
		}

		$criteria = new Pengin_Criteria();
		$criteria->add('id', 'IN', $ids);
		return $this->find($criteria);
	}

	/**
	 * 削除する.
	 * 
	 * @access public
	 * @param integer $id
	 * @return bool
	 */
	public function delete($id)
	{
		// リンクを削除
		$result = Pengin::getInstance()->getModelHandler('SetPropertyLink')->removeLink($id);

		if ( $result == false ) {
			return false;
		}

		// 本体を削除
		$result = parent::delete($id);

		if ( $result == false ) {
			return false;
		}

		return true;
	}
}
