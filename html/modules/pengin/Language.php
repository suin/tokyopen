<?php
class Pengin_Language extends SimpleXMLElement
{
	public function hasImports()
	{
		return isset($this->import);
	}

	public function getImports($defaultDirname = null)
	{
		$imports = array();

		foreach ( $this->import as $import ) {
			$dirname = $import->attributes()->dirname;
			$name    = $import->attributes()->name;

			if ( is_object($name) === false ) {
				continue; // TODO >> エラー通知が必要？
			} else {
				$name = reset($name);
			}

			if ( is_object($dirname) === false ) {
				$dirname = $defaultDirname;
			} else {
				$dirname = reset($dirname);
			}

			$imports[] = array(
				'dirname' => $dirname,
				'name'    => $name,
			);
		}

		return $imports;
	}

	public function getCatalogue()
	{
		$array = $this->asArray($this);

		if ( is_array($array['s']) and is_array($array['t']) )
		{
			$messages = @array_combine($array['s'], $array['t']);
		}
		else
		{
			$messages[$array['s']] = $array['t'];
		}

		return $messages;
	}

	public function asXML()
	{
		$string = parent::asXML();
		$this->_creanupXML($string);
		return $string;
	}

	public function asArray()
	{
		$this->_objectToArray($this);
		return $this;
	}

	protected function _creanupXML(&$string)
	{
		$string = preg_replace("/>\s*</", ">\n<", $string);
		$lines  = explode("\n", $string);
		$string = array_shift($lines) . "\n";
		$depth  = 0;

		foreach ( $lines as $line )
		{
			if ( preg_match('/^<[\w]+>$/U', $line) )
			{
				$string .= str_repeat("\t", $depth);
				$depth++;
			}
			elseif ( preg_match('/^<\/.+>$/', $line) )
			{
				$depth--;
				$string .= str_repeat("\t", $depth);
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
