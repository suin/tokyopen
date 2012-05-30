<?php
if (!defined('XOOPS_ROOT_PATH')) exit();
require_once XOOPS_MODULE_PATH.'/user/actions/UserInfoAction.class.php';

class UserinfoAction extends User_UserInfoAction
{
  public function executeViewSuccess(&$controller, &$xoopsUser, &$render)
  {
    $render->setTemplateName('message_userinfo.html');
    $render->setAttribute('thisUser', $this->mObject);
    $render->setAttribute('rank', $this->mRankObject);
    $render->setAttribute('pmliteUrl', $this->mPmliteURL);

    $userSignature = $this->mObject->getShow('user_sig');
    
    $render->setAttribute('user_signature', $userSignature);
    $render->setAttribute('searchResults', $this->mSearchResults);
    
    $user_ownpage = (is_object($xoopsUser) && $xoopsUser->get('uid') == $this->mObject->get('uid'));
    $render->setAttribute('user_ownpage', $user_ownpage);
    
    $render->setAttribute('self_delete', $this->mSelfDelete);
    if ($user_ownpage && $this->mSelfDelete) {
      $render->setAttribute('enableSelfDelete', true);
    } else {
      $render->setAttribute('enableSelfDelete', false);
    }
  }
}
?>