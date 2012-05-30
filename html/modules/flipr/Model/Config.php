<?php
class Flipr_Model_Config extends Pengin_Model_AbstractModel
{
	public $data = array();

	public function __construct()
	{
		$this->val('id', self::INTEGER, null);
		$this->val('name', self::STRING, null, 255);
		$this->val('value', self::TEXT, null);
	}

	public function setData($data)
	{
		$this->data = $data;

		$name = 'value';

		$value = $this->getVar('value');

		switch ( $data['value_type'] )
		{
			case 'bool' :
			{
				$this->val($name, self::BOOL);
				break;
			}
			case 'integer' :
			case 'int' :
			{
				$this->val($name, self::INTEGER);
				break;
			}
			case 'float' :
			{
				$this->val($name, self::FLOAT);
				break;
			}
			case 'text' :
			{
				$this->val($name, self::TEXT);
				break;
			}
			case 'datetime' :
			{
				$this->val($name, self::DATETIME);
				break;
			}
			case 'date' :
			{
				$this->val($name, self::DATE);
				break;
			}
			case 'string' :
			default:
			{
				$this->val($name, self::STRING);
				break;
			}
		}

		$this->setVar('value', $value);
	}
}
