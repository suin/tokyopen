<?php
/**
 * RSS2.0 Documentation
 * @see       http://cyber.law.harvard.edu/rss/rss.html
 */

/**
 * Usage:

	// <channel>を生成
	$siteName = '株式会社RYUS';
	$siteUrl  = 'http://ryus.co.jp';
	$siteDescription = 'RYUSは、XOOPS Cubeを活用したWebサイト/イントラネット向けサイト制作、XOOPS Cubeのモジュール開発/カスタマイズを専門に行っています。';
	$channel = new Pengin_Feed_Rss2_Channel($siteName, $siteUrl, $siteDescription);
	
	// 以下は随意に追加
	$channel->setLanguage('ja-jp');
	$channel->setCopyright('Copyright 株式会社 RYUS 2011');
	$channel->setManagingEditor('editor@example.com');
	$channel->setWebMaster('webmaster@example.com');
	$channel->setPubDate(strtotime('now'));
	$channel->setLastBuildDate(strtotime('now'));
	$channel->addCategory('XOOPS');
	$channel->addCategory('Pengin');
	$channel->addCategory('CMS');
	$channel->setGenerator('Pengin RSS Generator');
	$channel->setDocs('http://cyber.law.harvard.edu/rss/rss.html');
	
	// <item>を生成
	$itemName = 'XOOPSうさぎ通信 Vol.58発行しました♪';
	$itemUrl  = 'http://ryus.co.jp/modules/news/index.php?page=article&storyid=137';
	$itemContent = '<p class="itemText">XOOPSうさぎ通信58号発行のお知らせです♪<br /><br />今号の話題は…<br />１．仕事に使えるXOOPS♪GNAVI D3 ver0.96で会社の地図情報を一括表示する！<br />２．最新XOOPS Cube情報 オープンソースの利用拡大 IBMｉ導入手順書にXC2.1<br />３．うさできＱ＆Ａ『XCLパッケージの差分アップデートはやったほうがいい?』<br />４．イベント情報 第15回XCサタラボ 2月26日、OSC2011Tokyo/Spring 3月4,5日<br /><br />など盛りだくさんです！</p>';
	
	$item = new Pengin_Feed_Rss2_Item($itemName, $itemUrl, $itemContent);
	
	// 以下は随意に追加
	$item->setAuthor('admin@example.com', 'admin');
	$item->setComments('http://ryus.co.jp/modules/news/index.php?page=article&storyid=137#comments');
	$item->setEnclosure('http://example.com/usadeki.mp3', '5588242', 'audio/mpeg');
	$item->setGuid('http://ryus.co.jp/modules/news/index.php?page=article&storyid=137');
	$item->setPubDate(strtotime('now'));
	$item->setSource('http://example.com/source', 'Example dot com source');
	
	// <channel>に<item>を追加 (例では<item>をひとつだけ追加しているが複数追加可能)
	$channel->addItem($item);
	
	// <rss>を生成
	$rss = new Pengin_Feed_Rss2_Rss();
	
	// <rss>に<channel>を追加
	$rss->setChannel($channel);
	
	// XMLを描画
	echo $rss->asXML();

 * :end usage
 */

class Pengin_Feed_Rss2
{
	
}
