<?php
class Flipr_Model_Photo extends Pengin_Model_AbstractModel
{
    protected $dirname;
	public function __construct()
	{
	    $this->dirname = basename(dirname(dirname(__FILE__)));
	    
		$this->val('id', self::INTEGER);
		$this->val('name', self::STRING, '', 255);
		$this->val('desc', self::TEXT);
		$this->val('user_id', self::INTEGER);
		$this->val('album_id', self::INTEGER);
		$this->val('file_name', self::STRING, '', 255);
		$this->val('file_ext', self::STRING, '', 255);
		$this->val('file_title', self::STRING, '', 255);
		$this->val('file_mime', self::STRING, '', 255);
		$this->val('file_size', self::INTEGER);
		$this->val('file_width', self::INTEGER);
		$this->val('file_height', self::INTEGER);
		$this->val('view', self::INTEGER);
		$this->val('created', self::DATETIME);
		$this->val('modified', self::DATETIME);
	}

	public function getVars()
	{
		$vars = parent::getVars();
		$vars['url']        = $this->getPhotoUrl();
		$vars['basename']   = $this->getBaseName();
		$vars['thumb_url']  = $this->getThumbUrl();
		$vars['thumb_name'] = $this->getThumbName();
		return $vars;
	}

	public function getPhotoUrl()
	{
		$root =& Pengin::getInstance();
		$url = sprintf('%s/%s/%s', $root->cms->uploadUrl, $this->dirname, $this->getBaseName());
		return $url;
	}

	public function getPhotoPath()
	{
		$root =& Pengin::getInstance();
		$url = sprintf('%s/%s/%s', $root->cms->uploadPath, $this->dirname, $this->getBaseName());
		return $url;
	}

	public function getThumbUrl()
	{
		if ( file_exists($this->getThumbPath()) === false )
		{
			return false;
		}

		$root =& Pengin::getInstance();
		$url = sprintf('%s/%s/%s', $root->cms->uploadUrl, $this->dirname, $this->getThumbName());
		return $url;
	}

	public function getThumbPath()
	{
		$root =& Pengin::getInstance();
		$url = sprintf('%s/%s/%s', $root->cms->uploadPath, $this->dirname, $this->getThumbName());
		return $url;
	}

	public function getThumbName()
	{
		return $this->getVar('file_name').'_small.'.$this->getVar('file_ext');
	}

	public function getBaseName()
	{
		return $this->getVar('file_name').'.'.$this->getVar('file_ext');
	}
}
