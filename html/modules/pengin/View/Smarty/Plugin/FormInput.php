<?php
class Pengin_View_Smarty_Plugin_FormInput implements Pengin_View_Smarty_PluginInterface,
                                                     Pengin_View_Smarty_PluginFunctionInterface
{
	protected static $parameters = array(
		'property' => null,
	);

	public static function getType()
	{
		return self::TYPE_FUNCTION;
	}

	public static function getName()
	{
		return 'form_input';
	}

	public static function run(array $parameters, &$smarty)
	{
		$parameters = array_merge(self::$parameters, $parameters);

		$property = $parameters['property'];
		unset($parameters['property']);

		if ( self::_isPropertyObject($property) === false ) {
			return false;
		}

		$renderer    = $property->getHtmlRenderer();
		$_parameters = $property->getHtmlParameters();
		$parameters  = array_merge($_parameters, $parameters); // $parametersで上書き

		return call_user_func($renderer, $parameters);
	}

	protected static function _isPropertyObject($object)
	{
		if ( is_object($object) === false ) {
			return false;
		}

		if ( is_subclass_of($object, 'Pengin_Form_Property') === false ) {
			return false;
		}

		if ( ( $object instanceof Pengin_Form_Property_HtmlInterface ) === false ) {
			return false;
		}

		return true;
	}
}
