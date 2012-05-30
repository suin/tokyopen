<?php
$pengin = Pengin::getInstance();
$pengin->translator->useTranslation('addon_manager', $pengin->cms->langcode, 'translation');

$adminmenu[] = array(
	'title' => t("Addon Store"),
	'link'  => 'admin/index.php',
	'show'  => true,
);
