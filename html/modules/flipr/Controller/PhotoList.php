<?php
class Flipr_Controller_PhotoList extends Flipr_Abstract_Controller
{
	protected $albumId = 0;
	protected $albumHandler  = null;
	protected $albumModel    = null;
	protected $photoHandler  = null;
	protected $photoModels   = array();
	protected $photoTotal    = 0;

	protected $start = 0;
	protected $limit = 24;

	public function __construct()
	{
		parent::__construct();
		$this->_fetchPage();
		$this->_fetchAlbumId();
		$this->_getAlbumModelHandler();
		$this->_getAlbumModel();
		$this->_getPhotoHandler();
		$this->_setPageTitle();
	}

	public function main()
	{
		$this->_default();
	}

	protected function _default()
	{
		$this->_loadPhotoModels();
		$this->_countPhotos();
		$this->_assignAlbum();
		$this->_assignPhotos();
		$this->_assignPager();
		$this->_assignOutput();
		$this->_view();
	}

	protected function _fetchPage()
	{
		$this->start = $this->get('start');
	}

	protected function _fetchAlbumId()
	{
		$this->albumId = (int) $this->get('album');

		if ( $this->albumId < 1 )
		{
			$this->root->redirect("Please select album.", 'album_list');
		}
	}

	protected function _getAlbumModelHandler()
	{
		$this->albumHandler =& $this->root->getModelHandler('Album');
	}

	protected function _getAlbumModel()
	{
		$this->albumModel = $this->albumHandler->load($this->albumId);

		if ( $this->albumModel === false )
		{
			$this->root->redirect("Album not found.", 'album_list');
		}
	}

	protected function _getPhotoHandler()
	{
		$this->photoHandler =& $this->root->getModelHandler('Photo');
	}

	protected function _setPageTitle()
	{
		$this->pageTitle = $this->albumModel->getVar('name');
	}

	protected function _loadPhotoModels()
	{
		$this->photoModels = $this->photoHandler->find($this->_getPhotoCriteria(), 'id', 'DESC', $this->limit, $this->start);
	}

	protected function _countPhotos()
	{
		$this->photoTotal = $this->photoHandler->count($this->_getPhotoCriteria());
	}

	protected function &_getPhotoCriteria()
	{
		static $criteria = null;

		if ( $criteria === null )
		{
			$criteria = new Pengin_Criteria;
			$criteria->add('album_id', $this->albumId);
		}

		return $criteria;
	}

	protected function _assignPhotos()
	{
		$this->output['photos'] = array();

		foreach ( $this->photoModels as $photoModel )
		{
			$this->output['photos'][] = $photoModel->getVars();
		}
	}

	protected function _assignAlbum()
	{
		$this->output['album'] = $this->albumModel->getVars();
	}

	protected function _assignOutput()
	{
		$this->output['photo_total'] = $this->photoTotal;
		$this->output['album_id'] = $this->albumId;
	}

	protected function _assignPager()
	{
		$pager = new Pengin_Pager(array(
			'current' => $this->start, 
			'perPage' => $this->limit, 
			'total'   => $this->photoTotal,
		));

		if ( $this->limit < $this->photoTotal )
		{
			$this->output['pages'] = $pager->getPages();
		}
	}
}
