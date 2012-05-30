<?php
$pengin = Pengin::getInstance();
$pengin->translator->useTranslation('Nano', $pengin->cms->langcode, 'translation');

$adminmenu[] = array(
	'title' => t("Nano submenu1"),
	'link'  => 'admin/index.php?controller=submenu1',
	'show'  => true,
);
