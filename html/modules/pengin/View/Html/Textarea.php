<?php
class Pengin_View_Html_Textarea extends Pengin_View_Html_AbstractElement
                                implements Pengin_View_Html_ElementInterface
{
	public static function render(array $parameters = array())
	{
		$template = '<textarea%s>%s</textarea>';

		$contents = self::_shiftParameter('value', $parameters, '');
		$contents = htmlspecialchars($contents, ENT_QUOTES);
		$attributes = Pengin_TextFilter::buildAttributeString($parameters);

		return sprintf($template, $attributes, $contents);
	}
}
