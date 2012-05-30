<?php

define( 'SHORTEN_CLICKABLE_LENGTH' , 80 ) ;

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

class Legacy_TextFilterShortenClickable extends XCube_ActionFilter 
{
	function preBlockFilter()
	{
		$this->mRoot->mDelegateManager->add( 'Legacy_TextFilter.MakeClickableConvertTable' , array( &$this , 'hook' ) , XCUBE_DELEGATE_PRIORITY_3 ) ;
	}

	function hook( &$patterns, &$replacements )
	{
		$patterns = array(
			"/(^|\s)([a-z]+?):\/\/([^, \r\n\"\(\)'<>]+)(?=[\s\x80-\xff]|$)/ie",
			"/(^|\s)www\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)(?=[\s\x80-\xff]|$)/ie",
			"/(^|\s)ftp\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)(?=[\s\x80-\xff]|$)/i",
			"/(^|\s)([a-z0-9\-_\.]+?)@([^, \r\n\"\(\)'<>\[\]]+)(?=[\s\x80-\xff]|$)/i"
		);
		$replacements = array(
			"'\\1<a href=\"\\2://\\3\" target=\"_blank\">'.xoops_substr('\\2://\\3',0,SHORTEN_CLICKABLE_LENGTH).'</a>'",
			"'\\1<a href=\"http://www.\\2.\\3\" target=\"_blank\">www.'.xoops_substr('\\2.\\3</a>',0,SHORTEN_CLICKABLE_LENGTH).'</a>'",
			"\\1<a href=\"ftp://ftp.\\2.\\3\" target=\"_blank\">ftp.\\2.\\3</a>",
			"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"
		);
	}

}

?>