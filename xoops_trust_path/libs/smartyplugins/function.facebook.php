<?php
function smarty_function_facebook($params, &$smarty)
{

    if (empty($params['action'])) {
        $action = "like";
    } else {
        $action = $params['action'];
    }

	if ( isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on' )  
	{  
	    $protocol = 'https://';  
	}  
	else  
	{  
	    $protocol = 'http://';  
	}
  
	$url = htmlspecialchars($protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	if(substr($url,-1,1) == "/"){
		//$url.="index.php";
	}

	return '<iframe src="http://www.facebook.com/plugins/like.php?href='.$url.'&amp;layout=button_count&amp;show_faces=true&amp;width=180&amp;action='.$action.'&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:180px;height:20px;" allowTransparency="true"></iframe>';

}
?>
