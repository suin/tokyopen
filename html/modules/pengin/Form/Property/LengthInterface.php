<?php
/**
 * {header_doc}
 */

interface Pengin_Form_Property_LengthInterface
{
	/**
	 * Set length
	 * @param integer $length Length
	 * @returns object $this
	 */
	public function length($length);

	/**
	 * Returns length
	 * @returns integer Length
	 */
	public function getLength();

	/**
	 * Validate length
	 * @returns
	 */
	public function validateLength();
}
