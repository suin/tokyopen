<?php

class TPInstaller_Core_ClassLoader
{
	protected $includePaths       = array();
	protected $namespaceSeparator = '_';
	protected $fileExtension      = '.php';

	public function setIncludePath($includePath)
	{
		if ( in_array($includePath, $this->includePaths) === false )
		{
			$this->includePaths[] = $includePath;
		}

		return $this;
	}

	public function getIncludePath()
	{
		return $this->includePaths;
	}

	public function setNamespaceSeparator($namespaceSeparator)
	{
		$this->namespaceSeparator = $namespaceSeparator;
		return $this;
	}

	public function getNamespaceSeparator()
	{
		return $this->namespaceSeparator;
	}

	public function setFileExtension($fileExtension)
	{
		$this->fileExtension = $fileExtension;
		return $this;
	}

	public function getFileExtension()
	{
		return $this->fileExtension;
	}

	public function register()
	{
		spl_autoload_register(array($this, 'loadClass'));
		return $this;
	}

	public function unregister()
	{
		spl_autoload_unregister(array($this, 'loadClass'));
		return $this;
	}

	public function loadClass($className)
	{
		if ( class_exists($className, false) === true )
		{
			return;
		}

		if ( interface_exists($className, false) === true )
		{
			return;
		}

		if ( function_exists('trait_exists') === true and trait_exists($className, false) === true )
		{
			return;
		}

		if ( preg_match('/[a-zA-Z0-9_\\\]/', $className) == false )
		{
			throw new InvalidArgumentException('Invalid class name was given: '.$className);
		}

		$classFile = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $className);
		$classFile = $classFile.$this->fileExtension;

		foreach ( $this->includePaths as $includePath )
		{
			$classPath = $includePath.DIRECTORY_SEPARATOR.$classFile;
	
			if ( file_exists($classPath) === true )
			{
				require $classPath;
				return true;
			}
		}

		return false;
	}
}
