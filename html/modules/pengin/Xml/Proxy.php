<?php
/**
 * The proxy for Pengin_Xml class.
 */

abstract class Pengin_Xml_Proxy extends Pengin_Pattern_Proxy
{
	protected $xmlElementClass = 'Pengin_Xml';
	protected $subject = null;
	protected $rootElement = '';
	protected $encoding = 'UTF-8';

	public function __construct()
	{
		$xmlElementClass = $this->xmlElementClass;
		$dom = '<?xml version="1.0" encoding="'.$this->encoding.'" ?>'.$this->rootElement;
		$realSubject = new $xmlElementClass($dom);
		parent::__construct($realSubject);
	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function addChildXML($object)
	{
		if ( is_subclass_of($object, __CLASS__) === true )
		{
			$object = $object->getSubject();
		}

		return $this->subject->addChildXML($object);
	}
}
