<?php

interface Mailform_Plugin_PluginInterface
{
	/**
	 * プラグインの表示名を返す.
	 * @static
	 * @return string
	 * @note ここで設定したプラグイン表示名は、画面設定ページのパレットに現れます
	 */
	public static function getPluginName();

	/**
	 * モックHTMLを返す
	 * @return string
	 * @note ここで設定したHTMLは画面設定ページのパレットに現れます
	 */
	public static function getMockHTML();

	/**
	 * オプションのデフォルト値を返す
	 * @return array
	 */
	public function getDefaultPluginOptions();

	/**
	 * オプション設定画面のHTMLを出力する
	 * @param array $params パラメータ
	 * @return void
	 */
	public function editPluginOptions(array $params);

	/**
	 * オプションをバリデーションする
	 * @param array $params パラメータ
	 * @return array エラー文言の配列。エラーがない場合は空の配列を返す。
	 */
	public function validatePluginOptions(array $params);

	/**
	 * オプションを反映する
	 * @param array $options オプション
	 * @return void
	 * @note Mailform_Form_Confirm::setUpProperties()で使う
	 */
	public function applyPluginOptions(array $options);
}
