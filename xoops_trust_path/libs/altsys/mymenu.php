<?php

// submenu
$page = preg_replace( '/[^0-9a-zA-Z_-]/' , '' , @$_GET['page'] ) ;
if( file_exists( dirname(__FILE__).'/mymenusub/'.$page.'.php' ) ) {
	include dirname(__FILE__).'/mymenusub/'.$page.'.php' ;
}

?>