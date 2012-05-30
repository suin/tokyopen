<?php
$root =& XCube_Root::getSingleton();
define('_US_HASJUSTREG', sprintf("%s 管理者様へのお知らせ\n %s/\n\n次のユーザーが新規登録されました：%%s 様", $root->mContext->mXoopsConfig['sitename'], XOOPS_URL));
