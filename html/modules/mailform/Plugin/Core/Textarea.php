<?php

class Mailform_Plugin_Core_Textarea extends Pengin_Form_Property_Textarea
                                    implements Mailform_Plugin_PluginInterface
{
	/**
	 * プラグインの表示名を返す.
	 * @static
	 * @return string
	 */
	public static function getPluginName()
	{
		return t("Textarea");
	}

	/**
	 * モックHTMLを返す
	 * @return string
	 */
	public static function getMockHTML()
	{
		return '<textarea></textarea>';
	}

	/**
	 * オプションのデフォルト値を返す
	 * @return array
	 */
	public function getDefaultPluginOptions()
	{
		return array(
			'value' => '',
			'rows'  => '12',
			'cols'  => '60',
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
				<td colspan="1" class="head"><?php echo t("Default Value") ?></td>
				<td colspan="3" class="odd">
					<textarea name="value" cols="40" rows="10"><? echo $params['value'] ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="head"><?php echo t("Rows") ?></td>
				<td class="odd">
					<input type="text" name="rows" value="<? echo $params['rows'] ?>" size="3" maxlength="3" />
				</td>
				<td class="head"><?php echo t("Cols") ?></td>
				<td class="odd">
					<input type="text" name="cols" value="<? echo $params['cols'] ?>" size="3" maxlength="3" />
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

		if ( $this->_isValidNumber($params['rows']) === false ) {
			$errors[] = t("Rows").': '.t("Must be number integer than {1}", 1);
		}

		if ( $this->_isValidNumber($params['cols']) === false ) {
			$errors[] = t("Cols").': '.t("Must be number integer than {1}", 1);
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
		$this->attr('rows', $options['rows']);
		$this->attr('cols', $options['cols']);
	}

	protected function _isValidNumber($number)
	{
		if ( preg_match('/^[0-9]+$/', $number) == false ) {
			return false;
		}

		if ( $number < 1 ) {
			return false;
		}

		return true;
	}
}
