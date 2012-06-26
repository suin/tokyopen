<?php

class Mailform_Plugin_Core_Email extends Pengin_Form_Property_Email
                                 implements Mailform_Plugin_PluginInterface
{
	/**
	 * プラグインの表示名を返す.
	 * @static
	 * @return string
	 */
	public static function getPluginName()
	{
		return t("Email");
	}

	/**
	 * モックHTMLを返す
	 * @return string
	 */
	public static function getMockHTML()
	{
		return '<input type="text" value="sender@example.com" />';
	}

	/**
	 * オプションのデフォルト値を返す
	 * @return array
	 */
	public function getDefaultPluginOptions()
	{
		return array(
			'value' => '',
		);
	}

	/**
	 * オプション設定画面のHTMLを出力する
	 * @param array $params パラメータ
	 * @return void
	 */
	public function editPluginOptions(array $params)
	{
		?>
		<table class="outer">
			<tr>
				<td class="head"><?php echo t("Default Value") ?></td>
				<td class="odd">
					<input type="text" name="value" value="<? echo $params['value'] ?>" />
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * オプションをバリデーションする
	 * @param array $params パラメータ
	 * @return array エラー文言の配列。エラーがない場合は空の配列を返す。
	 */
	public function validatePluginOptions(array $params)
	{
		$errors = array();

		if ( mb_strlen($params['value']) > 0 ) {
			if ( $this->_isValidEmail($params['value']) === false ) {
				$errors[] = t('{1} is Invalid Email format.', t("Default Value"));
			}
		}

		return $errors;
	}

	/**
	 * オプションを反映する。
	 * @param array $options オプション
	 * @return void
	 * @note Mailform_Form_Confirm::setUpProperties()で使う
	 */
	public function applyPluginOptions(array $options)
	{
		// Nothing to do.
	}
}
