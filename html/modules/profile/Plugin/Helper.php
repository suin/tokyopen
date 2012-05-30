<?php

class Profile_Plugin_Helper
{
	const OPTION_SEPARATOR = "\n";

	/**
	 * 選択肢設定を文字列から配列に変換する.
	 * 
	 * @access public
	 * @static
	 * @param string $optionText
	 * @return array
	 */
	public static function unserializeOptions($optionText)
	{
		$options = explode(self::OPTION_SEPARATOR, $optionText);
		$options = array_map('trim', $options);
		$options = array_filter($options, 'strlen');
		$options = array_combine($options, $options);
		return $options;
	}

	/**
	 * 配列をJSON形式の文字列に変換する.
	 * 
	 * @access public
	 * @static
	 * @param mixed $value
	 * @return string JSON
	 */
	public static function serialize($value)
	{
		if ( is_array($value) === true and count($value) > 0 ) {
			$value = array_values($value); // 添字配列にする
			$value = json_encode($value);
		} else {
			$value = '';
		}

		return $value;
	}

	/**
	 * JSON形式の文字列を配列に変換する.
	 * 
	 * @access public
	 * @static
	 * @param string $value JSON
	 * @return void
	 */
	public static function unserialize($value)
	{
		$value = json_decode($value, true);

		if ( is_array($value) === false ) {
			$value = array(); // エラーの場合
		}
		
		return $value;
	}
}
