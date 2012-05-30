<?php
class RyusExmailString extends XCube_ActionFilter
{
	function preBlockFilter()
	{
		// XCL2.1.6ではタイトルのデリゲートはコールされてない
		$this->mRoot->mDelegateManager->add( 'UserMailjobObject.GetReplaceTitle' , array( $this , 'replaceTitle' ) ) ;
		$this->mRoot->mDelegateManager->add( 'UserMailjobObject.GetReplaceBody' , array( $this , 'replaceBody' ) ) ;
		
	}
	function replaceTitle(& $t_title, &$to_user, &$from_user)
	{
		$this->_replace($t_title, $to_user, $from_user);
	}
	/**
	 * 
	 * @param $t_body string メール本文
	 * @param $to_user XoopsUser 送り先 
	 * @param $from_user XoopsUser 送信者
	 * @return void
	 */
	function replaceBody(& $t_body, &$to_user, &$from_user)
	{
		$this->_replace($t_body, $to_user, $from_user);
	}
	
	/**
	 * 
	 * @param $t_body string メール本文
	 * @param $to_user XoopsUser 送り先 
	 * @param $from_user XoopsUser 送信者
	 * @return void
	 */
	function _replace(& $string, &$to_user, &$from_user)
	{
		/*
		 * ここに置き換えを追加すれば、本文もタイトルでも置き換えタグを拡張できます。
		 */
		$to_user_name = $to_user->get('name');
		$to_user_name = empty($to_user_name) ? $to_user->get('uname') : $to_user_name;
		$string = str_replace('{X_NAME}', $to_user_name, $string);
		$string = str_replace('{X_TODAY}', formatTimestamp(time(), 's', $to_user->get('timezone_offset')), $string);
		
	}
}
?>