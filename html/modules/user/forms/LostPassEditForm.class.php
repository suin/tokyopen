<?php
/**
 * @package user
 * @version $Id: LostPassEditForm.class.php,v 1.2 2007/06/07 05:27:37 minahito Exp $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once XOOPS_ROOT_PATH . "/core/XCube_ActionForm.class.php";

class User_LostPassEditForm extends XCube_ActionForm
{
	protected $tryFieldType = '';
	
	public function __construct($userConfig)
	{
		parent::__construct();
		$this->tryFieldType= $userConfig['lost_pass_method'];
	}

	function getTokenName()
	{
		return "module.user.LostPassEditForm.TOKEN";
	}

	function prepare()
	{
		//
		// Set form properties
		//
		$this->mFormProperties['try_field'] = new XCube_StringProperty('try_field');
		$this->mFormProperties['code'] = new XCube_StringProperty('code');

		//
		// Set field properties
		//
		if ( $this->tryFieldType == 'email' )
		{
			$this->mFieldProperties['try_field'] = new XCube_FieldProperty($this);
			$this->mFieldProperties['try_field']->setDependsByArray(array('required', 'email'));
			$this->mFieldProperties['try_field']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_EMAIL);
			$this->mFieldProperties['try_field']->addMessage('email', _MD_USER_ERROR_EMAIL, _MD_USER_LANG_EMAIL);
		}
		else
		{
			$this->mFieldProperties['try_field'] = new XCube_FieldProperty($this);
			$this->mFieldProperties['try_field']->setDependsByArray(array('required'));
			$this->mFieldProperties['try_field']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_UNAME);
		}
	}

	public function getTryFieldTitle()
	{
		if ( $this->tryFieldType == 'email' )
		{
			return _MD_USER_LANG_EMAIL;
		}
		else
		{
			return _MD_USER_LANG_UNAME;
		}
	}
}
