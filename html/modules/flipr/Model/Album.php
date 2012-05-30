<?php
class Flipr_Model_Album extends Pengin_Model_AbstractModel
{
	public function __construct()
	{
		$this->val('id', self::INTEGER);
		$this->val('name', self::STRING, '', 255);
		$this->val('desc', self::TEXT);
		$this->val('user_id', self::INTEGER);
		$this->val('cover_photo_id', self::INTEGER);
		$this->val('created', self::DATETIME);
		$this->val('modified', self::DATETIME);
	}

	public function getVars()
	{
		$vars = parent::getVars();
		$vars['total'] = $this->getPhotoTotal();
		$vars['cover'] = $this->getCoverPhoto();
		return $vars;
	}

	public function getPhotoTotal()
	{
		$root =& Pengin::getInstance();
		$criteria = new Pengin_Criteria;
		$criteria->add('album_id', $this->getVar('id'));
		$photoHandler = $root->getModelHandler('Photo');
		$photoTotal   = $photoHandler->count($criteria);
		return $photoTotal;
	}

	public function getCoverPhoto()
	{
		$root =& Pengin::getInstance();
		$coverPhotoId = $this->getVar('cover_photo_id');
		$photoHandler = $root->getModelHandler('Photo');
		$photoModel   = false;

		if ( $coverPhotoId > 0 )
		{
			$photoModel = $photoHandler->load($coverPhotoId);
		}

		if ( $photoModel === false )
		{
			$criteria = new Pengin_Criteria;
			$criteria->add('album_id', $this->getVar('id'));
			$photoModels = $photoHandler->find($criteria, 'id', 'DESC', 0, 1);
			$photoModel  = reset($photoModels);
		}

		if ( $photoModel === false )
		{
			$photoModel = $photoHandler->create();
		}

		return $photoModel->getVars();
	}
}
