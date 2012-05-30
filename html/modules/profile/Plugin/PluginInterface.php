<?php
interface Profile_Plugin_PluginInterface extends Pengin_Form_Property_HtmlInterface
{
	const GROUP_BASIC   = 32; // 汎用系グループ
	const GROUP_ACCOUNT = 64; // アカウント系グループ
	const GROUP_PROFILE = 128; // プロフィール系グループ

	/**
	 * プラグインの表示名を返す.
	 * 
	 * @access public
	 * @static
	 * @return string
	 */
	public static function getPluginName();

	/**
	 * カラム設定を返す.
	 * 
	 * @access public
	 * @return string
	 */
	public static function getDatabaseColumn();

	/**
	 * GROUP_BASIC, GRPUP_ACCOUNT, GROUP_PROFILE いずれかのグループ設定を返す.
	 * 
	 * @access public
	 * @static
	 * @return integer 
	 */
	public static function getGroup();

	/**
	 * オプションがあるかを返す.
	 * 
	 * @access public
	 * @static
	 * @return bool
	 */
	public static function hasOptions();

	/**
	 * オプションを配列にして返す.
	 * 
	 * @access public
	 * @static
	 * @return array
	 */
	public static function unserializeOptions($optionText);

	/**
	 * 表示機能があるかを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function hasRender();

	/**
	 * 値を表示する.
	 * 
	 * @access public
	 * @params $content
	 * @return string
	 */
	public function render($content);

	/**
	 * オリジナルのモデル更新機能があるかを返す.
	 * 
	 * @access public
	 * @return bool
	 */
	public function hasUpdateModel();

	/**
	 * モデルを更新する.
	 * 
	 * @access public
	 * @param Profile_Model_User $model
	 * @return void
	 */
	public function updateModel(Profile_Model_User $model);
}
