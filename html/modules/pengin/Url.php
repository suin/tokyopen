<?php
/**
 * URLを操作するためのクラス
 */

class Pengin_Url
{
	public static function getUrl()
	{
		if ( isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on' )
		{
			$protocol = 'https://';
		}
		else
		{
			$protocol = 'http://';
		}

		return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	public static function makeUrl($baseUrl, $dirname, $controller = null, $action = null, $extra = array(), $mode = 'user')
	{
		$params = array();

		$urlRoot = $baseUrl.'/'.$dirname.'/';

		if ( $controller )
		{
			$params[] = 'controller='.$controller;
		}

		if ( $action )
		{
			$params[] = 'action='.$action;
		}

		if ( is_array($extra) and count($extra) > 0 )
		{
			$params[] = http_build_query($extra);
		}

		if ( $mode === 'admin' )
		{
			$admin = 'admin/';
		}
		else
		{
			$admin = '';
		}

		$param = implode('&', $params);

		if ( isset($param[0]) )
		{
			$param = 'index.php?'.$param;
		}

		$url = $urlRoot.$admin.$param;

		return $url;
	}

	public static function parse($uri)
	{
		$parsed = parse_url($uri);

		if ( $parsed === false )
		{
			return false;
		}

		if ( isset($parsed['query']) )
		{
			parse_str($parsed['query'], $parsed['query']);
		}

		return $parsed;
	}

	public static function glue(array $parsed)
	{
		$uri = '';

		if ( isset($parsed['scheme']) )
		{
			$uri .= $parsed['scheme'].':';

			if ( strtolower($parsed['scheme']) !== 'mailto' )
			{
				$uri .= '//';
			}
		}

		if ( isset($parsed['user']) )
		{
			$uri .= $parsed['user'];

			if ( isset($parsed['pass']) )
			{
				$uri .= ':'.$parsed['pass'];
			}

			$uri .= '@';
		}

		if ( isset($parsed['host']) )
		{
			$uri .= $parsed['host'];
		}

		if ( isset($parsed['port']) )
		{
			$uri .= ':'.$parsed['port'];
		}

		if ( isset($parsed['path']) )
		{ 
			if ( isset($uri[0]) === false )
			{
				$uri .= '/';
			}
			
			$uri .= $parsed['path'];
		} 

		if ( isset($parsed['query']) )
		{
			$uri .= '?'.http_build_query($parsed['query']);
		}

		if ( isset($parsed['fragment']) )
		{
			$uri .= '#'.$parsed['fragment'];
		} 
	
		return $uri; 
	}

	/*
	public static function buildQuery(array $data, $prefix = '', $sep = '', $key = '')
	{ 
		$ret = array();

		foreach ( $data as $k => $v )
		{
			if ( is_int($k) and $prefix != null )
			{
				$k = $prefix.$k;
			}

			if ( !empty($key) or $key === 0 )
			{
				$k = $key.'['.$k.']';
			}

			if ( is_array($v) )
			{ 
				$ret[] = self::buildQuery($v, '', $sep, $k);
			}
			else
			{
				$ret[] = $k.'='.$v;
			}
		}

		if ( empty($sep) )
		{
			$sep = ini_get('arg_separator.output');
		}

		return implode($sep, $ret); 

		return $query;
	}
	*/
}
