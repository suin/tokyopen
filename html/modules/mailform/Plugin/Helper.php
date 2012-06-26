<?php

class Mailform_Plugin_Helper
{
	/**
	 * 文字列を配列に分割する
	 * @param string $text 分割対象の文字列
	 * @param string $separator 区切り文字列
	 * @return array
	 */
	public static function textToArray($text, $separator = "\n")
	{
		$array = explode($separator, $text); // 分割
		$array = array_map('trim', $array); // 各要素をtrim()にかける
		$array = array_filter($array, 'strlen'); // 文字数が0のやつを取り除く
		$array = array_values($array); // キーを連番に振り直す
		return $array;
	}
}
