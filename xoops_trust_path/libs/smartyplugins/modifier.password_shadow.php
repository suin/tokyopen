<?php
/**
 *	smarty modifier:password_shadow()
 *
 *	パスワードなんかを*****に変換
 *
 *	sample:
 *	<code>
 *	{"12345"|password_shadow}
 *	</code>
 *	<code>
 *	12,345
 *	</code>
 *
 *	@param	string	$string	フォーマット対象文字列
 *	@return	string	フォーマット済み文字列
 */
function smarty_modifier_password_shadow($string)
{
	if ($string === "" || $string == null) {
		return "";
	}
	return str_repeat('*', strlen($string));
}
