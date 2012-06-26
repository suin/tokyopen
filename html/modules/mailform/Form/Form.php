<?php

class Mailform_Form_Form extends Pengin_Form
{
	public function setUpForm()
	{
		$this->title(t("Form Preference"));
	}

	/**
	 * プロパティをセットアップするテンプレートメソッド
	 * 
	 * @access public
	 * @return void
	 */
	public function setUpProperties()
	{
		$this->add('mail_to_sender', 'RadioYesNo')
			->required()
			->label("Mail to Sender");

		$this->add('receiver_email', 'Textarea')
			->required()
			->label("Receiver Email")
			->description("You can specify emails multiply by email separated with comma.")
			->attr('cols', 50)
			->attr('rows', 3);
	}

	public function validateReceiverEmail(Pengin_Form_Property $property)
	{
		$value  = $property->getValue();
		$emails = $this->_splitEmail($value);

		if ( mb_strlen($value) > 0 and count($emails) == 0 ) {
			$this->addError(t("Please enter {1}.", t("Receiver Email")));
		} 

		foreach ( $emails as $email ) {
			if ( Pengin_Validator::email($email) === false ) {
				$this->addError(t("{1} is Invalid Email format.", $email));
			}
		}
	}

	protected function _splitEmail($email)
	{
		$array = explode(",", $email); // 分割
		$array = array_map('trim', $array); // 各要素をtrim()にかける
		$array = array_filter($array, 'strlen'); // 文字数が0のやつを取り除く
		$array = array_values($array); // これはキーを連番に振りなおしてるだけ
		return $array;
	}
}
