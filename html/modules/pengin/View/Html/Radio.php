<?php
class Pengin_View_Html_Radio extends Pengin_View_Html_AbstractElement
                             implements Pengin_View_Html_ElementInterface
{
	public static function render(array $parameters = array())
	{
		$options = self::_shiftParameter('options', $parameters, array());
		$checked = self::_shiftParameter('checked', $parameters);
		return self::_renderInputs($parameters, $options, $checked);
	}

	protected static function _renderInputs(array $defaultParameters, array $options, $checked)
	{
		$template = '<label><input%s />%s</label> ';
		$inputTags = '';

		foreach ( $options as $key => $value ) {
			$parameters = $defaultParameters;
			$parameters['value'] = $key;

			if ( $key == $checked ) {
				$parameters['checked'] = 'checked';
			}

			$attributes = Pengin_TextFilter::buildAttributeString($parameters);
			$contents   = htmlspecialchars($value, ENT_QUOTES);

			$inputTags .= sprintf($template, $attributes, $contents);
		}

		return $inputTags;
	}
}
