<?php
abstract class Pengin_Model_AbstractModel
{
	const BOOL     = 1;
	const INTEGER  = 2;
	const FLOAT    = 3;
	const STRING   = 4;
	const TEXT     = 5;
	const DATETIME = 6;
	const DATE     = 7;

	protected $vars = array();
	protected $new  = true;

	public function __construct()
	{
	}

	public function __isset($name)
	{
		return isset($this->vars[$name]);
	}

	public function __get($name)
	{
		return $this->vars[$name]['value'];
	}

	public function val($name, $type, $default = null, $size = null)
	{
		$this->vars[$name]['value']   = $default;
		$this->vars[$name]['type']    = $type;
		$this->vars[$name]['default'] = $default;

		if ( $type == self::INTEGER )
		{
			$this->vars[$name]['size'] = ($size) ? $size : 8;
		}
		elseif ( $type == self::STRING )
		{
			$this->vars[$name]['size'] = ($size) ? $size : 255;
		}
	}

	public function setNew()
	{
		$this->new = true;
	}

	public function unsetNew()
	{
		$this->new = false;
	}

	public function isNew()
	{
		return $this->new;
	}

	public function setVar($name, $value)
	{
		if ( !isset($this->vars[$name]) )
		{
			return false;
		}

		$type = $this->vars[$name]['type'];

		if ( self::BOOL == $type )
		{
			$value = ( $value ) ? true : false ;
		}
		elseif ( self::INTEGER == $type )
		{
			$value = intval($value);
		}
		elseif ( self::FLOAT == $type )
		{
			$value = floatval($value);
		}
		elseif ( self::STRING == $type )
		{
			$value = strval($value);
		}
		elseif ( self::TEXT == $type )
		{
			$value = strval($value);
		}
		elseif ( self::DATETIME == $type )
		{
			if ( $value === '0000-00-00 00:00:00' )
			{
				$value = 0;
			}
			elseif ( preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value) and strtotime($value) !== false )
			{
				$value = strtotime($value);
			}
		}
		elseif ( self::DATE == $type )
		{
			if ( $value === '0000-00-00' )
			{
				$value = 0;
			}
			elseif ( preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) and strtotime($value) !== false )
			{
				$value = strtotime($value);
			}
		}

		$this->vars[$name]['value'] = $value;
	}

	public function set($name, $value)
	{
		$this->setVar($name, $value);
	}

	public function getVar($name)
	{
		return $this->vars[$name]['value'];
	}

	public function get($name)
	{
		return $this->getVar($name);
	}

	public function setVars($vars)
	{
		foreach ( $this->vars as $key => $v )
		{
			if ( isset($vars[$key]) )
			{
				$this->setVar($key, $vars[$key]);
			}
		}
	}

	public function getVars()
	{
		$vars = array();

		foreach ( $this->vars as $name => $var )
		{
			$vars[$name] = $var['value'];
		}

		return $vars;
	}

	public function getVarSqlEscaped($name)
	{
		$type  = $this->vars[$name]['type'];
		$value = $this->vars[$name]['value'];

		if ( is_null($value) === true )
		{
			return $value;
		}

		if ( self::BOOL == $type )
		{
			return ( $value ) ? true : false ;
		}
		elseif ( self::INTEGER == $type )
		{
			return intval($value);
		}
		elseif ( self::FLOAT == $type )
		{
			return floatval($value);
		}
		elseif ( self::STRING == $type )
		{
			return mysql_real_escape_string($value); // todo : size check
		}
		elseif ( self::TEXT == $type )
		{
			return mysql_real_escape_string($value);
		}
		elseif ( self::DATETIME == $type )
		{
			if ( !$value )
			{
				return '0000-00-00 00:00:00';
			}

			return date('Y-m-d H:i:s', $value);
		}
		elseif ( self::DATE == $type )
		{
			if ( !$value )
			{
				return '0000-00-00';
			}

			return date('Y-m-d', $value);
		}
	}

	public function getVarsSqlEscaped()
	{
		$vars = array();

		foreach ( $this->vars as $name => $var )
		{
			$vars[$name] = $this->getVarSqlEscaped($name);
		}

		return $vars;
	}

	public function isKeyExists($name)
	{
		return isset($this->vars[$name]);
	}
}

?>
