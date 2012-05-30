<?php
define('_MD_USER_LANG_NAME', '氏名');
$root =& XCube_Root::getSingleton();
define('_MD_USER_LANG_HASJUSTREG', sprintf("%s 管理者様へのお知らせ\n %s/\n\n次のユーザーが新規登録されました：%%s 様", $root->mContext->mXoopsConfig['sitename'], XOOPS_URL));
