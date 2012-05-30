<?php
/**
 * バリエーションを行うクラス
 */

class Pengin_Validator
{
	/**
	 * メールアドレスの書式を検証する
	 * @param string $email
	 * @return bool 正しい場合 TRUE 正しくない場合 FALSE
	 */
	public static function email($email)
	{
		if ( preg_match("/^[_a-z0-9\-+!#$%&'*\/=?^`{|}~]+(\.[_a-z0-9\-+!#$%&'*\/=?^`{|}~]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $email) > 0 ) {
			return true;
		}

		return false;
	}
}
