<?php
class Flipr_Controller_AlbumList extends Flipr_Abstract_Controller
{
	protected $albumHandler = null;
	protected $albumModels  = array();

	protected $start = 0;
	protected $limit = 24;
	protected $total = 0;

	public function __construct()
	{
		parent::__construct();

		$this->start = $this->get('start');

		$this->albumHandler =& $this->root->getModelHandler('Album');
	}

	public function main()
	{
		if ( $this->action === 'upload' )
		{
			$this->_upload();
		}
		else
		{
			$this->_default();
		}
	}

	protected function _default()
	{
		$this->_loadAlbumModels();
		$this->_countAlbums();
		$this->_assignAlbums();
		$this->_assignPager();
		$this->_view();
		$this->_cleanupTmpFiles();
	}

	protected function _upload()
	{
		$this->_checkRolePhotoNew();
		$this->_default();
	}

	protected function _checkRolePhotoNew()
	{
		if ( $this->role->isInRole('photo_new') === false )
		{
			$this->root->redirect("Sorry, you don't have the permission to access this area.");
		}
	}

	protected function _loadAlbumModels()
	{
		$this->albumModels = $this->albumHandler->find(null, 'id', 'DESC', $this->limit, $this->start);
	}

	protected function _countAlbums()
	{
		$this->total = $this->albumHandler->count();
	}

	protected function _assignAlbums()
	{
		$albums = array();

		foreach ( $this->albumModels as $albumModel )
		{
			$albums[] = $albumModel->getVars();
		}

		$this->output['albums'] = $albums;
	}

	protected function _assignPager()
	{
		$pager = new Pengin_Pager(array(
			'current' => $this->start, 
			'perPage' => $this->limit, 
			'total'   => $this->total,
		));

		if ( $this->limit < $this->total )
		{
			$this->output['pages'] = $pager->getPages();
		}
	}

	protected function _cleanupTmpFiles()
	{
		Flipr_Library_TemporaryFileCleaner::cleanSomeTime();
	}
}
