<?php
class Flipr_Controller_AlbumEdit extends Flipr_Controller_AlbumNew
{
	protected $albumId = 0;

	protected function _initInput()
	{
		$this->input = array(
			'name'  => $this->albumModel->getVar('name'),
			'desc'  => $this->albumModel->getVar('desc'),
		);
	}

	protected function _setPageTitle()
	{
		$this->pageTitle = t("Edit Album");
	}

	protected function _fetchAlbumId()
	{
		$this->albumId = (int) $this->get('album');

		if ( $this->albumId < 1 )
		{
			$this->root->redirect("Please select album.", 'album_list');
		}
	}

	protected function _getAlbumModel()
	{
		$this->_fetchAlbumId();
		$this->albumModel = $this->albumHandler->load($this->albumId);

		if ( $this->albumModel === false )
		{
			$this->root->redirect("Album not found.", 'album_list');
		}
	}

	protected function _redirect()
	{
		$this->root->redirect("Saved album successfully.", 'album_list');
	}
}
