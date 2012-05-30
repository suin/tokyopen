<?php

abstract class SiteNavi_Abstract_BlockController extends Pengin_Controller_AbstractBlock
{
	public function main()
	{
		$method = $this->Action.'Action';

		if ( method_exists($this, $method) )
		{
			$this->$method();
		}
		else
		{
			throw new RuntimeException("Such an action {$method} is not found");
		}
	}

	abstract protected function _showAction(); // For user pages
	abstract protected function _editAction(); // For block-admin pages
}
