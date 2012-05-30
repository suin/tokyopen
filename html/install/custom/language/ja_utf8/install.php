<?php

header( 'Content-Type: text/html; charset=UTF-8' ) ;

define('_INSTALL_CL0_1', '　アーカイブのxoops_trust_pathをアップロードしたパスを指定してください。末尾には「/」を付加しないでください。最初からパスが入っているようなら、ほとんどの場合、特に変更する必要はありません。');

define('_INSTALL_CL1', 'サーバセッティング');
define('_INSTALL_CL1_MAINFILE', 'settings/definition.custom.phpへの追加書き込み');
define('_INSTALL_CL1_0', '推奨の設定です。この設定は必ずしも行わなければならないものではありません。');
define('_INSTALL_CL1_1', 'PHPへのメモリ割り当て値');
define('_INSTALL_CL1_2', '動作推奨値16Mを満たしています');
define('_INSTALL_CL1_3', '動作推奨値16Mを満たしていません。環境が許す場合は右の値を&quot;16M&quot;以上にしてください。');
define('_INSTALL_CL1_4', '(レンタルサーバなどの共用サーバでは割り当てメモリは制限されている可能性があります。)');

define('_INSTALL_CL1_5', 'installディレクトリの放置');
define('_INSTALL_CL1_6', '正しく&quot;Yes&quot;を入力すると、インストール後にもinstallディレクトリを放置しても警告が出ないようにします。&quot;Yes&quot;にするケースはスタンドアローンの環境で開発に使用する場合のみで、インターネット公開用はもちろんLAN内で公開される場合も推奨できません。');
define('_INSTALL_CL1_7', '入力値を変更しますか？');

define('_INSTALL_CL1_10', 'XOOPS_COOKIE_PATHの指定');
define('_INSTALL_CL1_11', 'セッション等を保存するクッキーのパスを明示的に指定する場合に変更します（通常は必要ありません）。末尾には「/」を付加しないでください。');

define('_INSTALL_CL1_OK', 'サーバの設定値は妥当な感じです。');
define('_INSTALL_CL1_NG', 'サーバの設定値はやや不適切かもしれませんが、動作はします。');



define('_INSTALL_CL1_HTACCESS', '.htaccessに書き込みの推奨');
define('_INSTALL_CL1_HTACCESS_TITLE', 'WebサーバがApacheで.htaccessを使用できる場合に推奨する内容です');
define('_INSTALL_CL1_HTACCESS_MSG', '自動的に作成はされません。.htaccessを作成し、FTPソフトなどでサーバ上のXOOPS_ROOT_PATHに設置してください');

define('_INSTALL_CL2', 'サーバセッティングの保存');
define('_INSTALL_CL2_1', 'サーバセッティングの保存に成功しました');
define('_INSTALL_CL2_2', 'サーバセッティングの保存に失敗しました');

define('_INSTALL_CL50', 'ファイルのアクセス権のチェック（追加分）');


/// custom 2nd-installer language catalogue
define("_INSTALL_CL3_AVAIL", "利用可能なテーマを選択してください");
define("_INSTALL_CL3_INFO", "テーマの情報");
define("_INSTALL_CL3_MAIN", "使用するテーマセット");
define("_INSTALL_CL3_MAIN_EXP", "基本のテーマとして用いるテーマを選んでください。");
define('_INSTALL_CL3_VERSION', "バージョン");
define('_INSTALL_CL3_RENDER', "レンダーシステム");
define('_INSTALL_CL3_FORMAT', "形式");
define('_INSTALL_CL3_AUTHOR', "作者");
define('_INSTALL_CL3_LICENCE', "ライセンス");
define('_INSTALL_CL3_GET_THE_LATEST_VERSION', "最新版を入手する");
