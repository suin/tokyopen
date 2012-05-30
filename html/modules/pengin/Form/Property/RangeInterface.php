<?php
/**
 * {header_doc}
 */

interface Pengin_Form_Property_RangeInterface
{
	/**
	 * Set min
	 * @param integer|float $min Min
	 * @returns object $this
	 */
	public function min($min);

	/**
	 * Returns min
	 * @returns integer|float Min
	 */
	public function getMin();

	/**
	 * Set max
	 * @param integer|float $max Max
	 * @returns object $this
	 */
	public function max($max);

	/**
	 * Returns max
	 * @returns integer|float Max
	 */
	public function getMax();

	/**
	 * Validate range
	 * @returns void
	 */
	public function validateRange();
}
