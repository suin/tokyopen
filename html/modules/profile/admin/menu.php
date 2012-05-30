<?php

$pengin = Pengin::getInstance();
$pengin->translator->useTranslation('profile', $pengin->cms->langcode, 'translation');

$adminmenu[] = array(
	'title' => t("User list"),
	'link'  => 'admin/index.php',
	'show'  => true,
);

$adminmenu[] = array(
	'title' => t("Manage properties"),
	'link'  => 'admin/index.php?controller=property_list',
	'show'  => true,
);
