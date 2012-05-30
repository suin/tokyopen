<?php
/**
 * {header_doc}
 */

interface Pengin_Form_Property_PatternInterface
{
	/**
	 * Set pattern
	 * @param string $pattern Pattern
	 * @returns object $this
	 */
	public function pattern($pattern);

	/**
	 * Set pattern
	 * @returns string Pattern
	 */
	public function getPattern();

	/**
	 * Validate pattern
	 * @returns void
	 */
	public function validatePattern();
}
