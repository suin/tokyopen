<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_RadioOnOff extends Pengin_Form_Property_Radio
{
	const ON  = 1;
	const OFF = 0;

	protected $options = array(
		self::ON  => "On",
		self::OFF => "Off",
	);
}
