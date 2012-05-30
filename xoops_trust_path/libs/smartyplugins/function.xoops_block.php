<?php

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     xoops_block
 * Version:  1.1
 * Date:     Nov 15, 2005
 * Author:	 Tom Hayakawa <GEH01523@nifty.com>
 * Purpose:  fetch block, assign to template var	/ for XOOPS
 *
 * Version:
 *		1.0	Jul15,2005		First reliese
 * 		1.1	Nov15,2005		include block language file
 *
 * -------------------------------------------------------------
 * Input:    mod = module dir name
 * 			 file = module block file name
 *			 func = block function name
 *			 opt = function option (as required)
 *           assign = template var to assign parsed data
 *
 * Examples 1: 
 *	<{xoops_block mod="news"
 *			 func="b_news_top_show" opt="published,10,50" assign="newsblock"}>
 *	<ul>
 *	  <{foreach item=news from=$newsblock.stories}>
 *	    <li><a href="<{$xoops_url}>/modules/news/article.php?storyid=<{$news.id}>"><{$news.title}></a> (<{$news.date}>)</li>
 *	  <{/foreach}>
 *	</ul>
 *
 * Examples 2: 
 *	<{xoops_block file="modules/news/blocks/news_top.php"
 *			 func="b_news_top_show" opt="published,10,50" assign="newsblock"}>
 *	<ul>
 *	  <{foreach item=news from=$newsblock.stories}>
 *	    <li><a href="<{$xoops_url}>/modules/news/article.php?storyid=<{$news.id}>"><{$news.title}></a> (<{$news.date}>)</li>
 *	  <{/foreach}>
 *	</ul>
 * -------------------------------------------------------------
 **/


function smarty_function_xoops_block ($params, &$smarty)
{
	if ( !isset($params['assign']) ) {
		echo "no assign value";
		return;
	}

	if ( isset($params['mod']) ) {
		if ( !isset($params['func']) ) {
			echo "no func value";
			return;
		}
		global $xoopsConfig, $xoopsDB;
		$sql = "SELECT func_file FROM " .$xoopsDB->prefix("newblocks"). " WHERE dirname='".$params['mod']."' AND show_func='".$params['func']."' ";
		$result = $xoopsDB->query($sql);
		list( $file_name ) = $xoopsDB->fetchRow($result);
		$block_file = XOOPS_ROOT_PATH . "/modules/" . $params['mod'] . "/blocks/" . $file_name ;

		$lang_path = XOOPS_ROOT_PATH."/modules/".$params['mod']."/language/";
		if ( file_exists( $lang_path. $xoopsConfig['language']. "/blocks.php" ) ) {
		    require_once $lang_path. $xoopsConfig['language']."/blocks.php";
		} else {
		    require_once $lang_path . "english/blocks.php";
		}

	} elseif ( isset($params['file']) ) {
		$block_file = XOOPS_ROOT_PATH . "/" .$params['file'];

	} else {
		echo "no value";
		return;
	}

	if( file_exists($block_file) ) {
		require_once $block_file;
		if ( function_exists($params["func"]) ) {
			$option = explode( "," , $params['opt'] );
			eval ('$block=$params["func"]($option);');
			$smarty->assign( $params['assign'], $block );

		} else {
			echo "missing function";
			return;
		}

	}else {
		echo "missing file";
		return;
	}
}

?>