<?php
class Pengin_View
{
	public static function getView($engineName)
	{
		$class  = 'Pengin_View_'.$engineName;
		$engine = new $class;
		$engine->name = $engineName;
		return $engine;
	}
}

?>
