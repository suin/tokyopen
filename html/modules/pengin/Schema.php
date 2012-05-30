<?php
class Pengin_Schema
{
	public static function getLocalSchema($controller = null, $action = null)
	{
		$schema = array();

		if ( $controller !== null )
		{
			$schema[] = $controller;
		}
		else
		{
			$schema[] = 'default';
		}

		if ( $action !== null )
		{
			$schema[] = $action;
		}
		else
		{
			$schema[] = 'default';
		}

		$schema = implode('.', $schema);

		return $schema;
	}

	public static function getGlobalSchema($dirname = null, $controller = null, $action = null)
	{
		$schema = array();

		if ( $dirname !== null )
		{
			$schema[] = $dirname;
		}
		else
		{
			$schema[] = PENGIN_DIR; // TODO >> ^^;;
		}

		$schema[] = self::getLocalSchema($controller, $action);

		$schema = implode('.', $schema);

		return $schema;
	}

	public static function parse($schema)
	{
		if ( preg_match('/^(?:([a-z0-9_]+)\.)?([a-z0-9_]+)\.([a-z0-9_]+)$/', $schema, $matches) === 0 )
		{
			return false;
		}

		$parsed = array();

		if ( $matches[1] )
		{
			$parsed['dirname'] = $matches[1];
		}

		$parsed['controller'] = $matches[2];
		$parsed['action']     = $matches[3];

		return $parsed;
	}
}
