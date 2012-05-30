<?php
/**
 * {header_doc}
 */

class Pengin_Form_Property_RadioYesNo extends Pengin_Form_Property_Radio
{
	const YES = '1';
	const NO  = '0';

	protected $options = array(
		self::YES => "Yes",
		self::NO  => "No",
	);
}
