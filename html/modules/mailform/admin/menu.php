<?php
$dirname = basename(dirname(dirname(__FILE__)));
$pengin = Pengin::getInstance();
$pengin->translator->useTranslation($dirname, $pengin->cms->langcode, 'translation');

/*

$adminmenu[] = array(
	'title' => t("Mailform submenu1"),
	'link'  => 'admin/index.php?controller=submenu1',
	'show'  => true,
);
*/