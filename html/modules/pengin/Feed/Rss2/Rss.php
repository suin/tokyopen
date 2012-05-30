<?php
/**
 * Hanling <rss> element in RSS2.0
 */

class Pengin_Feed_Rss2_Rss extends Pengin_Xml_Proxy
{
	protected $rootElement = '<rss />';
	protected $version = '2.0';

	public function __construct()
	{
		parent::__construct();
		$this->addAttribute('version', $this->version);
	}

	public function setChannel(Pengin_Feed_Rss2_Channel $channel)
	{
		return $this->addChildXML($channel);
	}
}
