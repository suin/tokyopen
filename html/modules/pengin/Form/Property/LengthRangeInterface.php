<?php
/**
 * {header_doc}
 */

interface Pengin_Form_Property_LengthRangeInterface
{
	public function longest($length);
	
	public function getLongest();
	
	public function shortest($length);
	
	public function getShortest();

	public function validateLengthRange();
}
