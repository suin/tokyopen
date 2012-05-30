<?php
/// here is admin_extra Menu Language Message Catalogue

define('_MI_USER_EXTRA_ADMENU_USER_DATA_DOWNLOAD', 'ユーザ情報のダウンロード');
define('_AD_USER_EXTRA_DATA_DOWNLOAD_TIPS1', 'UserID順でユーザー情報の一括ダウンロードができます。');
define('_AD_USER_EXTRA_DATA_NUM', '%d名のユーザーが登録されています。'); 
define('_AD_USER_EXTRA_DATA_DOWNLOAD_DO', 'CSV形式でダウンロードする');

define('_MI_USER_EXTRA_ADMENU_USER_DATA_CSVUPLOAD', 'ユーザーの一括登録');
define('_AD_USER_EXTRA_DATA_UPLOAD_TIPS1', 'CSVファイルによるユーザーの一括登録が可能です。');
define('_AD_USER_EXTRA_DATA_UPLOAD_TIPS2', 'CSVファイルは<a href="?action=UserDataDownload" style="color:#941d55;font-weight:bold;">'._MI_USER_EXTRA_ADMENU_USER_DATA_DOWNLOAD.'</a>からダウンロードしたものを使ってください。列の増減は行わないでください');
define('_AD_USER_EXTRA_DATA_UPLOAD_TIPS3', 'CSVファイルには情報を更新・新規登録したいユーザーのみ記述してください。');
define('_AD_USER_EXTRA_DATA_UPLOAD_TIPS4', '一番左のUIDの列を0または空にすると新規ユーザーとして登録します。');
define('_AD_USER_EXTRA_DATA_UPLOAD_TIPS5', '一番左のUIDの列の値がある場合は、そのユーザー情報を更新します。<br>(パスワードを再設定する場合は30Byte以内で入力してください)');
define('_AD_USER_EXTRA_DATA_UPLOAD_SELECT_USER_CSVFILE', '登録するCSVファイルを選択してください。');
define('_AD_USER_EXTRA_DATA_UPLOAD_CHECK_USER_CSVFILE', '登録内容を確認してください。');
define('_AD_USER_EXTRA_DATA_UPLOAD_CONF', '登録内容を確認する');
define('_AD_USER_EXTRA_DATA_UPLOAD_BACK', 'CSVファイルを選択しなおす');
define('_AD_USER_EXTRA_DATA_UPLOAD_DO', '登録する');
define('_AD_USER_EXTRA_DATA_UPLOAD_DONE', 'CSVデータによるユーザーデータの更新を行いました。');

