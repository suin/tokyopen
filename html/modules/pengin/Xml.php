<?php
class Pengin_Xml extends SimpleXMLElement
{
	public function asXML()
	{
		$string = parent::asXML();
		$this->_creanupXML($string);
		return $string;
	}

	public function __toString()
	{
		return $this->asXML();
	}

	public function asArray()
	{
		$this->_objectToArray($this);
		return $this;
	}

	public function addChild($name, $value = null, $namespace = null)
	{
		// SimpleXML doesn't support '&' escaping.
		// And if $value contains '&', PHP reports warning error!!
		while ( $rest = preg_match('/&([^a][^m][^p][^;])/', $value, $matches) )
		{
			$value = preg_replace('/&([^a][^m][^p][^;])/', '&amp;$1', $value);
		}

		return parent::addChild($name, $value, $namespace);
	}

	public function setChild($name, $value = null)
	{
		if ( isset($this->$name) === true )
		{
			unset($this->$name);
		}

		return $this->addChild($name, $value);
	}

	public function addChildXML($append)
	{
		if ( strlen(trim(strval($append))) === 0 )
		{
			$xml = $this->addChild($append->getName());

			foreach ( $append->children() as $child )
			{
				$xml->addChildXML($child);
			}
		}
		else
		{
			$xml = $this->addChild($append->getName(), strval($append));
		}

		foreach ( $append->attributes() as $n => $v )
		{
			$xml->addAttribute($n, $v);
		}

		return $xml;
	}

	protected function _creanupXML(&$string)
	{
		$string = preg_replace("/>\s*</", ">\n<", $string);
		$lines  = explode("\n", $string);
		$string = array_shift($lines) . "\n";
		$depth  = 0;

		foreach ( $lines as $line )
		{
			if ( preg_match('#^</.+>$#', $line) )
			{
				$depth--;
				$string .= str_repeat("\t", $depth);
			}
			elseif ( preg_match('#^<[^>]+[^/]>$#U', $line) )
			{
				$string .= str_repeat("\t", $depth);
				$depth++;
			}
			else
			{
				$string .= str_repeat("\t", $depth);
			}

			$string .= $line . "\n";
		}

		$string = trim($string);
	}

	protected function _objectToArray(&$object)
	{
		if ( is_object($object) ) $object = (array) $object;
		if ( !is_array($object) ) return;

		foreach ( $object as &$member )
		{
			$this->_objectToArray($member);
		}
	}
}

?>
