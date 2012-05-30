<?php
/**
 * こういう( http://wex.im/ )スライドショーをXOOPS Cubeで使えるようにするプリロード
 *
 * PHP Version 5.2.4 or Upper version
 *
 * @package    JquerySlideShow
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia/>
 * @copyright  2010 Hidehito NOZAWA
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 *
 */

/*
使い方

まず、このファイルを /preload フォルダにアップロードします。
次に、テーマにタグを埋め込みます。埋め込み方は下の説明を御覧ください。

-----------------------------------------------------

[基本編] テーマで使う
画像の大きさを指定して、画像のURLを1行ずつ列挙するだけ

<{slideshow width=680 height=80}>
http://example.com/path/to/image1.png
http://example.com/path/to/image2.png
http://example.com/path/to/image3.png
<{/slideshow}>

-----------------------------------------------------

[応用編] 画像のリンク先を指定する
画像のURLの次に、半角スペースを挟んでリンク先のURLを指定すると、画像にリンクがつきます。

<{slideshow width=680 height=80}>
http://example.com/path/to/image1.png http://example.com/page1.html
http://example.com/path/to/image2.png http://example.com/page2.html
http://example.com/path/to/image3.png http://example.com/page3.html
<{/slideshow}>

-----------------------------------------------------

[応用編] 画像のURLに<{$xoops_url}>を使う
画像のURLに<{$xoops_url}>を使うと、サイトのURLが変わったときに、URLを書き換える必要がないので便利です

<{slideshow width=680 height=80}>
<{$xoops_url}>/uploads/myalbum/1.png <{$xoops_url}>/modules/pico/index.php?content_id=1
<{$xoops_url}>/uploads/myalbum/2.png <{$xoops_url}>/modules/pico/index.php?content_id=2
<{$xoops_url}>/uploads/myalbum/3.png <{$xoops_url}>/modules/pico/index.php?content_id=3
<{/slideshow}>

-----------------------------------------------------

[応用編] カスタムブロックで使う
カスタムブロックの「コンテンツ」に次のようなコードを埋め込みます。
カスタムブロックの「タイプ」は「PHPスクリプト」を指定します。

$params = array(
  'width' => 680, // 幅指定
  'height' => 80, // 縦指定
);
$images ="
http://example.com/path/to/image1.png
http://example.com/path/to/image2.png
http://example.com/path/to/image3.png
";
JquerySlideShow::renderSlideShow($params, $images);


*/

defined('XOOPS_ROOT_PATH') or die;

class JquerySlideShow extends XCube_ActionFilter
{
	protected $javaScriptFile = '/js/vendor/jquery.slider.min.js';
	protected $styleSheetFile = '/js/vendor/jquery.slider.css';

	protected static $selectorCount = 0;
	protected static $defaultParams = array(
		'width'        => null,
		'height'       => null,
		'wait'         => null,
		'fade'         => null,
		'direction'    => null,
		'showControls' => null,
		'showProgress' => null,
		'hoverPause'   => null,
		'autoplay'     => null,
	);

	public function preBlockFilter()
	{
		$this->mRoot->mDelegateManager->add('Legacy_RenderSystem.SetupXoopsTpl', array(&$this, 'hook'));
	}

	public function hook(&$xoopsTpl)
	{
		$moduleHeader  = $xoopsTpl->get_template_vars('xoops_module_header');
		$moduleHeader .= $this->_getHeaderTags();
		$xoopsTpl->assign('xoops_module_header', $moduleHeader);
		$xoopsTpl->register_block('slideshow', array(get_class($this), 'renderSlideShow'));
	}

	/**
	 * スライドショー用のHTMLを描画する関数
	 */
	public static function renderSlideShow($params, $content, &$smarty = null, &$repeat = null)
	{
		$urls   = self::_getUrls($content);
		$params = self::_getParams($params);

		if ( count($urls) === 0 )
		{
			return;
		}

		$options = json_encode((object)$params);
		$options = preg_replace('/"(\w+)":/', '$1:', $options);

		$selector = 'slider'.self::$selectorCount;

		self::_renderImages($selector, $urls);
		self::_renderScript($selector, $options);

		self::$selectorCount += 1;
	}

	protected static function _getUrls($content)
	{
		$content = explode("\n", $content);
		$content = array_map('trim', $content);
		$content = array_diff($content, array(''));

		$urls = array();

		foreach ( $content as $line )
		{
			$line  = str_replace("\t", ' ', $line);
			$parts = explode(' ', $line);
			$parts = array_map('trim', $parts);
			$parts = array_diff($parts, array(''));
			$parts = array_values($parts);

			if ( strpos($line, 'http') === 0 )
			{
				$urls[] = $parts;
			}
		}

		return $urls;
	}

	protected static function _getParams($params)
	{
		$params = array_diff($params, array_diff_key($params, self::$defaultParams));
		$params = array_merge(self::$defaultParams, $params);
		$params = array_diff($params, array(null));
		return $params;
	}

	protected static function _renderImages($selector, $urls)
	{
		$dom  = '<div id="'.$selector.'">';

		foreach ( $urls as $url )
		{
			$link = null;

			if ( count($url) >= 2 )
			{
				list($image, $link) = $url;
				$dom .= '<div class="image"><a href="'.$link.'"><img src="'.$image.'" /></a></div>';
			}
			else
			{
				list($image) = $url;
				$dom .= '<div class="image"><img src="'.$image.'" /></div>';
			}
		}

		$dom .= '</div>';

		echo $dom;
	}

	protected static function _renderScript($selector, $options)
	{
		$script = '<script type="text/javascript">jQuery(function($){  $("#'.$selector.'").slider('.$options.');  });</script>';
		echo $script;
	}


	protected function _getHeaderTags()
	{
		$tag  = '<script type="text/javascript" src="'.XOOPS_URL.$this->javaScriptFile.'"></script>'."\n";
		$tag .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.$this->styleSheetFile.'" />';
		return $tag;
	}

}
