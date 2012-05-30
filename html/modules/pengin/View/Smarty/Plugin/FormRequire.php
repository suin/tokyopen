<?php
class Pengin_View_Smarty_Plugin_FormRequire implements Pengin_View_Smarty_PluginInterface,
                                                       Pengin_View_Smarty_PluginFunctionInterface
{
	protected static $defaultParameters = array(
		'property' => null,
		'required' => true,
		'sign'     => '[required]',
	);

	public static function getType()
	{
		return self::TYPE_FUNCTION;
	}

	public static function getName()
	{
		return 'form_require';
	}

	public static function run(array $parameters, &$smarty)
	{
		$parameters = array_merge(self::$defaultParameters, $parameters);

		$property = $parameters['property'];

		if ( is_object($property) === true and $property instanceof Pengin_Form_Property ) {
			$parameters['required'] = $property->isRequired();
		}

		if ( $parameters['required'] === true ) {
			$sign = t($parameters['sign']);
			$sign = sprintf(' <span class="formRequired">%s</span>', $sign);
			return $sign;
		}

		return '';
	}
}
