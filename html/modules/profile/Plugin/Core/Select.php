<?php

class Profile_Plugin_Core_Select extends Pengin_Form_Property_Select
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
		return "Select";
	}

	/**
	 * カラム設定を返す.
	 * 
	 * @access public
	 * @return string
	 */
	public static function getDatabaseColumn()
	{
		return 'VARCHAR(255)';
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
		return 3;
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
		return true;
	}

	/**
	 * unserializeOptions function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $optionText
	 * @return void
	 */
	public static function unserializeOptions($optionText)
	{
		return Profile_Plugin_Helper::unserializeOptions($optionText);
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
		return htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); // TODO
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
