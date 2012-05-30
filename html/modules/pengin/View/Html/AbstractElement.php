<?php
class Pengin_View_Html_AbstractElement
{
	/**
	 * _shiftParameter function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $name
	 * @param array &$parameters
	 * @param mixed $default (default: null)
	 * @return void
	 */
	protected static function _shiftParameter($name, array &$parameters, $default = null)
	{
		if ( array_key_exists($name, $parameters) === true ) {
			$value = $parameters[$name];
			unset($parameters[$name]);
		} else {
			$value = $default;
		}

		return $value;
	}
}
