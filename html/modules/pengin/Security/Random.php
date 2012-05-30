<?php
/**
 * 乱数クラス
 */

class Pengin_Security_Random
{
	const UPPER_CASE_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const LOWER_CASE_CHARS = 'abcdefghijklmnopqrstuvwxyz';
	const NUMBER_CHARS     = '0123456789';

	/**
	 * ランダムな文字列を返すする.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function getRandomChars($length = 8)
	{
		return self::_getRandomChars(self::UPPER_CASE_CHARS . self::LOWER_CASE_CHARS . self::NUMBER_CHARS, $length);
	}

	/**
	 * ランダムな数字文字列を返すする.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function getRandomNumbers($length = 8)
	{
		return self::_getRandomChars(self::NUMBER_CHARS);
	}

	protected static function _getRandomChars($chars, $length)
	{
		while ( strlen($chars) < $length ) {
			$chars .= $chars;
		}

		return substr(str_shuffle($chars), 0, $length);
	}
}
