<?php
$pengin = Pengin::getInstance();
$pengin->translator->useTranslation('footer', $pengin->cms->langcode, 'translation');



$adminmenu[] = array(
	'title' => t("Footer Menu List"),
	'link'  => 'admin/index.php?controller=menu_list',
	'show'  => true,
);
