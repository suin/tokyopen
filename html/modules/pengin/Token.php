<?php
class Pengin_Token
{
	public static function issue($timeout = 180)
	{
		$root =& Pengin::getInstance();

		$expire = time() + intval($timeout);
		$token  = self::_getToken($root);
		$tokens = $root->session('tokens');

		if ( !is_array($tokens) )
		{
			$tokens = array();
		}

		if ( count($tokens) >= 5 )
		{
			asort($tokens);
			$tokens = array_slice($tokens, -4, 4);
		}

		$tokens[$token] = $expire;

		$root->session('tokens', $tokens);

		return $token;
	}

	public static function check($stub)
	{
		$root =& Pengin::getInstance();

		$tokens = $root->session('tokens');

		if ( !isset($tokens[$stub]) )
		{
			return false;
		}

		if ( time() >= $tokens[$stub] )
		{
			unset($tokens[$stub]);
			$root->session('tokens', $tokens);
			return false;
		}

		unset($tokens[$stub]);
		$root->session('tokens', $tokens);

		return true;
	}

	protected static function _getToken(&$root)
	{
		$name  = $root->context->controller;

		$salt  = self::_getSalt();
		$salt  = md5($salt);

		$token = uniqid().mt_rand();
		$token = md5($token);

		return $name.$salt.$token;
	}

	protected static function _getSalt()
	{
		if ( defined('XOOPS_SALT') )
		{
			return XOOPS_SALT;
		}

		if ( defined('XOOPS_DB_PREFIX') )
		{
			return XOOPS_DB_PREFIX;
		}

		return __FILE__;
	}
}
