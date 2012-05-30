<?php
//  
    $cl_exists = @include_once dirname(__FILE__).'/language/'.$language.'/install.php';
    if( ! $cl_exists ) require_once dirname(__FILE__).'/language/english/install.php';
	
    $wizard->setBaseTemplate('./custom/templates/install_theme.html');
    
//  $wizardSeq->add('install_<currentwizard>.php',  '<h3>タイトル',   'install_<nextwizard>.php','次へボタンの接頭辞');
//	$wizardSeq->insertAfter('install_<aftertarget>.php', 'install_<currentwizard>.php',  '<h3>タイトル',   'install_<backwizard>.php','戻るボタンの接頭辞');
	$wizardSeq->replaceAfter('modcheck', 'dbform_hd', _INSTALL_L90);

	$wizardSeq->replaceAfter('dbform_hd', 'dbconfirm_hd', _INSTALL_L53, '', _INSTALL_L93);

	$wizardSeq->replaceAfter('dbconfirm_hd', 'dbsave_hd', _INSTALL_L92, 'mainfile', _INSTALL_L94);

	$wizardSeq->insertAfter('mainfile', 'extramodcheck', _INSTALL_CL50, 'start', _INSTALL_L103);
	$wizardSeq->insertAfter('extramodcheck', 'serversettings', _INSTALL_CL1, 'extramodcheck', _INSTALL_L82);
	$wizardSeq->insertAfter('serversettings', 'serversettings_save', _INSTALL_CL2, 'serversettings', _INSTALL_CL1);
//	$wizardSeq->insertAfter('trustpathform', 'trustpathconfirm', 'confirm Xoops Trust Path');
//	$wizardSeq->insertAfter('trustpathconfirm', 'trustpathsave', 'save Xoops Trust Path');
//  $wizardSeq->replaceAfter('createTables', 'siteInit',  _INSTALL_L116, 'insertData_hd',     _INSTALL_L117); // ...replaceAfter example
    $wizardSeq->replaceAfter('createTables', 'siteInit_hd',  _INSTALL_L116, 'insertData_hd',     _INSTALL_L117);
    $wizardSeq->add('insertData_hd',  _INSTALL_L116, 'finish',     _INSTALL_L117);
