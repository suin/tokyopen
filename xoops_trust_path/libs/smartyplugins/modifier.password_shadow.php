<?php
/**
 *	smarty modifier:password_shadow()
 *
 *	�p�X���[�h�Ȃ񂩂�*****�ɕϊ�
 *
 *	sample:
 *	<code>
 *	{"12345"|password_shadow}
 *	</code>
 *	<code>
 *	12,345
 *	</code>
 *
 *	@param	string	$string	�t�H�[�}�b�g�Ώە�����
 *	@return	string	�t�H�[�}�b�g�ςݕ�����
 */
function smarty_modifier_password_shadow($string)
{
	if ($string === "" || $string == null) {
		return "";
	}
	return str_repeat('*', strlen($string));
}
