<?php
abstract class Profile_Plugin_AbstractPlugin extends Pengin_Form_Property
                                             implements Profile_Plugin_PluginInterface
{
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
		return self::GROUP_PROFILE;
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
	 * HTML用のコールバック関数を返す.
	 * 
	 * @access public
	 * @return string|array コールバック関数
	 */
	public function getHtmlRenderer()
	{
		return array($this, 'renderInput');
	}

	/**
	 * HTMLの設定情報を返す.
	 * 
	 * @access public
	 * @return array key-value形式の設定情報
	 */
	public function getHtmlParameters()
	{
		return array();
	}

	/**
	 * 入力タグを生成する
	 * 
	 * @access public
	 * @param array $parameters 設定情報
	 * @return string HTMLタグ
	 */
	public function renderInput(array $parameters = array())
	{
		return '';
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
	 * 値を表示する.
	 * 
	 * @access public
	 * @params $content
	 * @return string
	 */
	public function render($content)
	{
		return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
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
		// 何もしない
	}
}
