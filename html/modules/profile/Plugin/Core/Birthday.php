<?php

class Profile_Plugin_Core_Birthday extends Profile_Plugin_AbstractPlugin
{
	protected static $month = array(
		1  => 'January',
		2  => 'February',
		3  => 'March',
		4  => 'April',
		5  => 'May',
		6  => 'June',
		7  => 'July',
		8  => 'August',
		9  => 'September',
		10 => 'October',
		11 => 'November',
		12 => 'December',
	);

	/**
	 * プラグインの表示名を返す.
	 * 
	 * @access public
	 * @static
	 * @return string
	 */
	public static function getPluginName()
	{
		return "Birthday";
	}

	/**
	 * カラム設定を返す.
	 * 
	 * @access public
	 * @return string
	 */
	public static function getDatabaseColumn()
	{
		return 'DATE';
	}

	/**
	 * HTMLの設定情報を返す.
	 * 
	 * @access public
	 * @return array key-value形式の設定情報
	 */
	public function getHtmlParameters()
	{
		return array(
			'name'  => $this->name,
			'value' => $this->value,
		);
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
		$name  = $parameters['name'];
		$year  = $parameters['value']['year'];
		$month = $parameters['value']['month'];
		$day   = $parameters['value']['day'];

		$monthOptions = $this->_getMonthOptions();
		$dayOptions   = $this->_getDayOptions();

		$year = htmlspecialchars($year, ENT_QUOTES, 'UTF-8');
		$yearInput = sprintf('<input type="text" name="%s[year]" value="%s" size="4" maxlength="4" />', $name, $year);
		$monthInput = sprintf('<select name="%s[month]">', $name);

		foreach ( $monthOptions as $value => $label ) {
			if ( $value == $month ) {
				$monthInput .= sprintf('<option value="%s" selected="selected">%s</option>', $value, t($label));
			} else {
				$monthInput .= sprintf('<option value="%s">%s</option>', $value, t($label));
			}
		}

		$monthInput .= '</select>';

		$dayInput = sprintf('<select name="%s[day]">', $name);
		
		foreach ( $dayOptions as $value => $label ) {
			if ( $value == $day ) {
				$dayInput .= sprintf('<option value="%s" selected="selected">%s</option>', $value, $label);
			} else {
				$dayInput .= sprintf('<option value="%s">%s</option>', $value, $label);
			}
		}

		$dayInput .= '</select>';

		$input = t("year:{1} month:{2} day:{3}", $yearInput, $monthInput, $dayInput);

		return $input;
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
	public function render($value)
	{
		if ( $value == '' ) {
			return '';
		}

		$date = getdate($value);
		$month = $date['mon'];
		$month = self::$month[$month];
		$month = t($month);
		$birthday = t("year:{1} month:{2} day:{3}", $date['year'], $month, $date['mday']);
		$age = self::_getAge($date['year'], $date['mon'], $date['mday']);
		$age = t("{1} years old", $age);
		
		$content = $birthday.' '.$age;

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

	/**
	 * 値を人が分かるような形で表現する.
	 * 
	 * @access public
	 * @return string
	 */
	public function describeValue()
	{
		return implode('-', $this->value);
	}

	/**
	 * describeValue()の結果を翻訳して返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function describeValueLocal()
	{
		$year  = $this->value['year'];
		$month = $this->value['month'];
		$day   = $this->value['day'];

		if ( $year == '' and $month == '' and $day == '' ) {
			return '';
		}

		$birthday = t("year:{1} month:{2} day:{3}", $year, $month, $day);

		return $birthday;
	}

	/**
	 * バリデーションを実行する.
	 * 
	 * @access public
	 * @return Pengin_Form_Property
	 */
	public function validate()
	{
		parent::validate();
		$this->_validateDatetime();
		return $this;
	}

	/**
	 * 入力値を保存可能な書式で返す.
	 * 
	 * @access public
	 * @return string
	 */
	public function exportValue()
	{
		$year  = $this->value['year'];
		$month = $this->value['month'];
		$day   = $this->value['day'];

		if ( $year == '' and $month == '' and $day == '' ) {
			return null;
		}

		$value = sprintf('%04d-%02d-%02d', $this->value['year'], $this->value['month'], $this->value['day']);
		return $value;
	}

	/**
	 * 保存可能な書式で入力値をセットする.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return Pengin_Form_Property
	 */
	public function importValue($value)
	{
		if ( $value == '' ) {
			return array(
				'year'  => '',
				'month' => '',
				'day'   => '',
			);
		}

		$tokens = explode('-', date('Y-m-d', $value));

		$this->value = array(
			'year'  => $tokens[0],
			'month' => $tokens[1],
			'day'   => $tokens[2],
		);
		return $this;
	}

	/**
	 * 必須テストの合否を返す.
	 * 
	 * @access protected
	 * @param mixed $value
	 * @return bool
	 */
	protected function _testRequired($value)
	{
		return ( $value['year'] and $value['month'] and $value['day'] );
	}

	protected function _validateDatetime()
	{
		$year  = $this->value['year'];
		$month = $this->value['month'];
		$day   = $this->value['day'];

		$monthOptions = $this->_getMonthOptions();
		$dayOptions   = $this->_getDayOptions();

		if ( mb_strlen($year) > 0 and preg_match('/^[0-9]{4}$/', $year) == false ) {
			$this->addError(t("Year must be number.", $this->getLabelLocal()));
		}

		if ( array_key_exists($month, $monthOptions) === false ) {
			$this->addError(t("Please select month."));
		}

		if ( array_key_exists($day, $dayOptions) === false ) {
			$this->addError(t("Please select day."));
		}

		if ( $year or $month or $day ) { // どれか入力されていたら
			if ( ($year and $month and $day) === false ) { // 全部がTRUEじゃないとき、つまり入力済じゃないとき
				$this->addError(t("Please complete input all of year, month and day."));
			}
		}

		if ( $this->hasError() === true ) {
			return; // 年月日が数値以外の場合にcheckdate()でエラーが起きるので
		}

		if ( $this->required === true and checkdate($month, $day, $year) === false ) {
			$this->addError(t("Please input valid date."));
		}
	}

	protected static function _getMonthOptions()
	{
		$month = array('' => '');
		$month += self::$month;
		return $month;
	}

	protected static function _getDayOptions()
	{
		$day = array('' => '');
		
		for ( $d = 1; $d <= 31; $d ++ ) {
			$day[$d] = $d;
		}

		return $day;
	}

	protected static function _getAge($year, $month, $day)
	{
		$birthday = sprintf('%04d%02d%02d', $year, $month, $day);
		$today    = date('Ymd');
		$age = floor( ($today - $birthday) / 10000 );
		return $age;
	}
}
