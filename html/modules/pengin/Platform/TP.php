<?php

class Pengin_Platform_TP extends Pengin_Platform_Xoops20
{
	public function __construct()
	{
		$this->rootPath   = TP_ROOT_PATH;
		$this->modulePath = $this->rootPath.'/modules';
		$this->uploadPath = $this->rootPath.'/uploads';

		$this->url       = TP_URL;
		$this->moduleUrl = $this->url.'/modules';
		$this->uploadUrl = $this->url.'/uploads';
		$this->trustPath = TP_TRUST_PATH;

		$this->cachePath = TP_CACHE_PATH;

		$this->charset  = _CHARSET;
		$this->langcode = _LANGCODE;
	}
}
