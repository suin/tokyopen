<?php


function smarty_function_xanhte_form_name($params, &$smarty)
{
	extract($params);	// $name = form_input_name 

	$ctl =& Ethna_Controller::getInstance();
	$ae =& $ctl->getActionError();

	// ���݃A�N�e�B�u�ȃA�N�V�����t�H�[���ȊO�̃t�H�[����`��
	// ���p����ꍇ�ɂ�Smarty�ϐ��𒇉����(���܂������H)
	$app = $smarty->get_template_vars('app');
	if (isset($app['__def__']) && $app['__def__'] != null) {
		if (isset($app['__def__'][$name])) {
			$def = $app['__def__'][$name];
		}
	} else {
		$af =& $ctl->getActionForm();
		$def = $af->getDef($name);
	}

	if (is_null($def) || isset($def['name']) == false) {
		$form_name = $name;
	} else {
		$form_name = $def['name'];
	}

	if ($ae->isError($name)) {
		// ���̓G���[�̏ꍇ�̕\��
//		print '<span class="error">' . $form_name . '</span>';
		print $form_name ;
	} else {
		// �ʏ펞�̕\��
//		print '<span class="">' . $form_name . '</span>';
		print $form_name ;
	}
	if (isset($def['required']) && $def['required'] == true) {
		// �K�{���̕\��
		print '<span class="required">*</span>';
	}



}