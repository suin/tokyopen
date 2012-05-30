<?php
abstract class Profile_Abstract_AdminController extends Profile_Abstract_Controller
{
	final public function main()
	{
		$actionMethod = $this->Action.'Action';

		if ( method_exists($this, $actionMethod) === false )
		{
			$this->root->redirect(t("Page not found."), XOOPS_URL);
		}

		$this->$actionMethod();
	}
}
