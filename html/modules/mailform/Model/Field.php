<?php
class Mailform_Model_Field extends Pengin_Model_AbstractModel
{
	const JSON_ARRAY = 999;

	public function __construct()
	{
		$this->val('id', self::INTEGER, null, 11);
		$this->val('form_id', self::INTEGER, null, 11);
		$this->val('name', self::STRING, null, 255);
		$this->val('label', self::STRING, null, 255);
		$this->val('type', self::STRING, null, 100);
		$this->val('required', self::INTEGER, null, 1);
		$this->val('weight', self::INTEGER, null, 3);
		$this->val('description', self::TEXT, null);
		$this->val('options', self::JSON_ARRAY, null);
		$this->val('created', self::DATETIME, null);
		$this->val('creator_id', self::INTEGER, null, 8);
		$this->val('modified', self::DATETIME, null);
		$this->val('modifier_id', self::INTEGER, null, 8);
	}

	public function setVar($name, $value)
	{
		if ( isset($this->vars[$name]) === false ) {
			return false;
		}

		$type = $this->vars[$name]['type'];

		if ( $type == self::JSON_ARRAY ) {

			if ( is_string($value) === true ) {
				$value = json_decode($value, true);
			}
	
			if ( is_array($value) === false ) {
				$value = array();
			}

			$this->vars[$name]['value'] = $value;
		} else  {
			parent::setVar($name, $value);
		}
	}

	public function getVarSqlEscaped($name)
	{
		$type  = $this->vars[$name]['type'];

		if ( $type == self::JSON_ARRAY ) {
			$json = json_encode($this->vars[$name]['value']);
			return mysql_real_escape_string($json);
		} else  {
			return parent::getVarSqlEscaped($name);
		}
	}
}
