<?php
abstract class Pengin_Pattern_Proxy
{
	protected $subject = null;

	public function __construct($realSubject)
	{
		$this->subject = $realSubject;
	}

	public function __get($name)
	{
		return $this->subject->$name;
	}

	public function __set($name, $value)
	{
		$this->subject->$name = $value;
	}

	public function __isset($name)
	{
		return isset($this->subject->$name);
	}

	public function __unset($name)
	{
		unset($this->subject->$name);
	}

	public function __call($method, $args)
	{
		return call_user_func_array(array($this->subject, $method), $args);
	}
}
