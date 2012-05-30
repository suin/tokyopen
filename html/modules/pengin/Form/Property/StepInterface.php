<?php
/**
 * {header_doc}
 */

interface Pengin_Form_Property_StepInterface
{
	/**
	 * Set step
	 * @param integer|float $step Step
	 * @returns object $this
	 */
	public function step($step);

	/**
	 * Returns step
	 * @returns integer|float Step
	 */
	public function getStep();

	/**
	 * Validate step
	 * @returns void
	 */
	public function validateStep();
}
