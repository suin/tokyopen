<?php
class Pengin_View_Html_Select extends Pengin_View_Html_AbstractElement
                              implements Pengin_View_Html_ElementInterface
{
	public static function render(array $parameters = array())
	{
		$template = '<select%s>%s</select>';

		$options   = self::_shiftParameter('options', $parameters, array());
		$selected  = self::_shiftParameter('selected', $parameters);
		$optionTag = self::_renderOptions($options, $selected);

		$attributes = Pengin_TextFilter::buildAttributeString($parameters);

		return sprintf($template, $attributes, $optionTag);
	}

	protected static function _renderOptions(array $options, $selected = null)
	{
		$template = '<option%s>%s</option>';

		$optionTag = '';

		foreach ( $options as $key => $value ) {
			$parameters = array(
				'value' => $key,
			);

			if ( $key == $selected ) {
				$parameters['selected'] = 'selected';
			}

			$attributes = Pengin_TextFilter::buildAttributeString($parameters);
			$contents   = htmlspecialchars($value, ENT_QUOTES);

			$optionTag .= sprintf($template, $attributes, $contents);
		}

		return $optionTag;
	}
}
