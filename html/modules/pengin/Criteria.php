<?php
/**
 * 使い方
	$criteria = new Pengin_Criteria(); // 'OR'を引数にするとORで結合
	$criteria->add('foo', 123);
	$criteria->add('bar', '!=', 456);
	$criteria->add('tama', '^=', 'nya'); // 'tama' LIKE 'nya%';
	$criteria->add('tora', '$=', 'nya'); // 'tora' LIKE '%nya';
	$criteria->add('mike', '*=', 'nya'); // 'mike' LIKE '%nya%';

	// 'siro' IN (1,2,3);
	$criteria->add('siro', 'IN', array(1,2,3));
	$criteria->add('siro', 'IN', '(1,2,3)');

	// クライテリアオブジェクトもわたせるよ
	$criteria->add('chii', $subCriteria);

	$sql = "SELECT * FROM nekoneko WHERE ".$criteria->render();
	
 */

class Pengin_Criteria
{
	protected $criteria = array();
	protected $andor    = 'AND';

	protected $operators = array(
		'=', '>', '>=', '<', '<=', '!=', '^=', '$=', '*=', 'IN', 'NOT IN',
	);

	protected static $acceptValueTypes = array('boolean', 'integer', 'double', 'string', 'array', 'NULL');

	public function __construct($andor = null)
	{
		if ( $andor === 'OR' )
		{
			$this->andor = 'OR';
		}
		else
		{
			$this->andor = 'AND';
		}
	}

	public function __toString()
	{
		return $this->render();
	}

	public function add()
	{
		try
		{
			$params = func_get_args();
			$num    = func_num_args();
	
			if ( $num === 1 and is_object($params[0]) )
			{
				$this->criteria[] = $params[0];
				return;
			}
			elseif ( $num === 2 )
			{
				$name     = $params[0];
				$value    = $params[1];
				$operator = reset($this->operators);
			}
			elseif ( $num === 3 )
			{
				$name     = $params[0];
				$value    = $params[2];
				$operator = $params[1];
			}
			else
			{
				throw new Exception(__METHOD__." expects 1, 2 or 3 parameters", E_USER_ERROR);
			}
	
			if ( is_string($name) === false )
			{
				throw new Exception(__METHOD__." expects first parameter is string", E_USER_ERROR);
			}
	
			if ( in_array(gettype($value), self::$acceptValueTypes) === false )
			{
				throw new Exception("Invalid type value was given to ".__METHOD__, E_USER_ERROR);
			}
	
			if ( in_array($operator, $this->operators) === false )
			{
				throw new Exception("Invalid operator was given to ".__METHOD__, E_USER_ERROR);
			}
		}
		catch ( Exception $e )
		{
				trigger_error($e->getMessage(), E_USER_ERROR);
				return false;
		}

		$this->criteria[] = array($name, $value, $operator);
		return true;
	}

	public function render()
	{
		$conditions = array();
		$likes = array('^=', '$=', '*=');

		foreach ( $this->criteria as $criterion )
		{
			if ( is_object($criterion) )
			{
				$conditions[] = '( '.$criterion->render().' )';
				continue;
			}

			list($name, $value, $operator) = $criterion;

			$prefix  = '';
			$postfix = '';

			if ( $operator === '*=' )
			{
				$prefix  = '%';
				$postfix = '%';
			}
			elseif ( $operator == '^=' )
			{
				$prefix  = '';
				$postfix = '%';
			}
			elseif ( $operator == '$=' )
			{
				$prefix  = '%';
				$postfix = '';
			}

			$prefix  = "'".$prefix;
			$postfix = $postfix."'";

			if ( is_array($value) )
			{
				foreach ( $value as $k => $v )
				{
					
					$value[$k] = mysql_real_escape_string($value[$k]);
					$value[$k] = $prefix.$value[$k].$postfix;
				}

				$value = implode(',', $value);
				$value = '('.$value.')';
			}
			else
			{
				$value = mysql_real_escape_string($value);
				$value = $prefix.$value.$postfix;
			}

			if ( in_array($operator, $likes) )
			{
				$operator = 'LIKE';
			}

			$name = '`'.$name.'`';

			$conditions[] = $name.' '.$operator.' '.$value;
		}

		$condition = implode(' '.$this->andor.' ', $conditions);
		return $condition;
	}
}
