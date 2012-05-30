<?php
class Pengin_TextFilter
{
	public static function escapeHtmlArray($vars)
	{
		foreach ( $vars as $key => $var )
		{
			if ( is_array($var) )
			{
				$vars[$key] = self::escapeHtmlArray($var);
			}
			elseif ( is_string($var) === true )
			{
				$vars[$key] = self::escapeHtml($var);
			}
		}

		return $vars;
	}

	public static function escapeHtml($string)
	{
		return htmlspecialchars($string, ENT_QUOTES);
	}

	/**
	 * 連想配列からHTMLの属性文字列を生成する.
	 * 
	 * @access public
	 * @static
	 * @param array $attributes key-value配列
	 * @param bool $withWhiteSpacePrefix 頭に半角スペースをつけるか
	 * @return string 属性文字列
	 */
	public static function buildAttributeString(array $attributes, $withWhiteSpacePrefix = true)
	{
		$attributeString = array();

		foreach ( $attributes as $key => $value ) {
			$value = htmlspecialchars($value, ENT_QUOTES);
			$attributeString[] = sprintf('%s="%s"', $key, $value);
		}

		$attributeString = implode(' ', $attributeString);

		if ( $withWhiteSpacePrefix === true and strlen($attributeString) > 0 ) {
			$attributeString = ' '.$attributeString;
		}

		return $attributeString;
	}
}
