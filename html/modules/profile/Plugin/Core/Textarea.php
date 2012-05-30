<?php

class Profile_Plugin_Core_Textarea extends Pengin_Form_Property_Textarea
                                   implements Profile_Plugin_PluginInterface
{
	/**
	 * プラグインの表示名を返す.
	 * 
	 * @access public
	 * @static
	 * @return string
	 */
	public static function getPluginName()
	{
		return "Textarea";
	}

	/**
	 * カラム設定を返す.
	 * 
	 * @access public
	 * @return string
	 */
	public static function getDatabaseColumn()
	{
		return 'TEXT';
	}

	/**
	 * グループ設定を返す.
	 * 
	 * @access public
	 * @static
	 * @return integer 
	 */
	public static function getGroup()
	{
		return 2;
	}

	/**
	 * オプションがあるかを返す.
	 * 
	 * @access public
	 * @static
	 * @return bool
	 */
	public static function hasOptions()
	{
		return false;
	}

	/**
	 * オプションを配列にして返す.
	 * 
	 * @access public
	 * @static
	 * @return array
	 */
	public static function unserializeOptions($optionText)
	{
		return array();
	}

	/**
	 * 表示機能があるかを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function hasRender()
	{
		return true;
	}

	/**
	 * 値を描画する.
	 * 
	 * @access public
	 * @params $content
	 * @return string
	 */
	public function render($content)
	{
		$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
		$content = nl2br($content);
		return $content;
	}

	/**
	 * オリジナルのモデル更新機能があるかを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public function hasUpdateModel()
	{
		return false;
	}

	/**
	 * モデルを更新する.
	 * 
	 * @access public
	 * @param Profile_Model_User $model
	 * @return void
	 */
	public function updateModel(Profile_Model_User $model)
	{
		// なにもしない
	}
}
