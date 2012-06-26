<?php

class Mailform_Plugin_Core_Checkbox extends Pengin_Form_Property_Checkbox
                                    implements Mailform_Plugin_PluginInterface
{
	/**
	 * プラグインの表示名を返す.
	 * @static
	 * @return string
	 */
	public static function getPluginName()
	{
		return t("Checkbox");
	}

	/**
	 * モックHTMLを返す
	 * @return string
	 */
	public static function getMockHTML()
	{
		return '<span style="white-space:nowrap"><input type="checkbox" checked="checked" /> A <input type="checkbox" /> B <input type="checkbox" /> C</span>';
	}

	/**
	 * オプションのデフォルト値を返す
	 * @return array
	 */
	public function getDefaultPluginOptions()
	{
		return array(
			'value' => '',
			'options' => '',
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
				<td class="head"><?php echo t("Options") ?></td>
				<td class="odd">
					<textarea name="options" cols="40" rows="10"><? echo $params['options'] ?></textarea>
					<div><?php echo t("You can specify options multiply by option separated with line break.") ?></div>
				</td>
			</tr>
			<tr>
				<td class="head"><?php echo t("Default Value") ?></td>
				<td class="odd">
					<textarea name="value" cols="40" rows="3"><? echo $params['value'] ?></textarea>
					<div><?php echo t("You can specify options multiply by option separated with line break.") ?></div>
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
		$selections = $options['options']; // 選択肢
		$selections = Mailform_Plugin_Helper::textToArray($selections);
		$selections = array_combine($selections, $selections);
		$this->options($selections);

		$values = $options['value']; // デフォルト値
		$values = Mailform_Plugin_Helper::textToArray($values);
		$this->value($values);
	}
}
