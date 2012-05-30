<?php
class Pengin_View_Html_Input extends Pengin_View_Html_AbstractElement
                             implements Pengin_View_Html_ElementInterface
{
	public static function render(array $parameters = array())
	{
		$template = '<input%s />';
		$parameters = Pengin_TextFilter::buildAttributeString($parameters);
		return sprintf($template, $parameters);
	}
}
