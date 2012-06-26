<?php

class Mailform_Controller_Form extends Pengin_Controller_AbstractThreeStepForm
{
	protected $id = null;
	protected $fieldModels = array();
	protected $form_form = array();
	
	protected $mailBodyTemplate;
	protected $mailBody;
	protected $xoopsConfig = array();
	protected $toEmail;
	protected $toName;
	protected $formValue;
	
	protected $sendToSender = false;
	
	
	protected $translateArray = array();

	public function __construct()
	{
		parent::__construct();
	}

	protected function _setUp()
	{
		$this->id = $this->root->request('id');
		$this->_getTableValue();
		$this->_setUpForm();
	}
	protected function _setUpForm()
	{
		$this->form = new Mailform_Form_Confirm($this->fieldModels);
	}
	
	protected function _useInputTemplate()
	{
		$this->template = 'pen:mailform.form.default.tpl';
	}
	
	protected function _useConfirmTemplate()
	{
		$this->template = 'pen:mailform.form.confirm.tpl';
	}

/* 入力画面を表示する */
	protected function _inputAction()
	{
		// default
		if (isset($this->form_form['submit'])){
			if($this->form_form['submit'] == ''){
				$this->form_form['submit'] = t('submit');
			}
		} else {
			$this->form_form['submit'] = t('submit');
		}

		$this->output['id'] = $this->id;
		$this->output['form_form'] = $this->form_form;
		$this->output['form'] = $this->form;

		$this->_adminTaskBar();

		parent::_inputAction();
	}

	
	/* **関係テーブルから値を参照する** */
	protected function _getTableValue()
	{
		$criteria = new Pengin_Criteria();
		// テーブルを見る
		$formHandler = $this->root->getModelHandler('Form');
		$formModel = $formHandler->load($this->id);

		if ( is_object($formModel) === false or $formModel->isNew() === true ) {
			$this->root->redirect("no contents here", $this->root->cms->url);
		}

		$fieldHandler = $this->root->getModelHandler('Field');
		$criteria->add('form_id', $this->id);		
		$fieldModels = $fieldHandler->find($criteria, 'weight');
		
		if ( is_array($fieldModels) === false or count($fieldModels) === 0 ) {
			if ( $this->root->cms->isAdmin() === true ) {
				$this->root->location('field_edit', null, array('id' => $this->id));
			} else {
				$this->root->redirect("no contents here", $this->root->cms->url);
			}
		}

		$this->fieldModels = $fieldModels;
		$this->form_form = $formModel->getVars();
	}
	
	/* お問い合わせ登録 */
	protected function _updateData()
	{
		$mailToSender = $this->form_form['mail_to_sender'] ;
		$mailToReceiver = $this->form_form['mail_to_receiver'] ;
		if(($mailToSender == 0) and ($mailToReceiver == 0)){
			return;
		}
		
		// xoopsConfig
		$cms =& $this->root->cms;
		$this->xoopsConfig=$cms->getConfig();

		// 宛先と名前をgetする。フォームの内容を編集する。
		$this->formValue = '';
		foreach($this->form->getProperties() as $property){
			$propertyArray = $property->toArray();
			if ( $property instanceof Mailform_Plugin_Core_Email ) {
				$this->toEmail = $propertyArray['value'];
			} elseif( $property instanceof Mailform_Plugin_Core_Name ) {
				$this->toName = $propertyArray['value'];
			}

			// TODO 値の描画機能は各プラグインに持たせる >>
			if ( is_array($propertyArray['value']) === true ) {
				$propertyArray['value'] = implode(', ', $propertyArray['value']);
			}

			if(strpos($propertyArray['value'],"\n") > 0){
				// 改行があったときは項目名の次の行に送る
				$this->formValue .= $propertyArray['label'].":\n".$propertyArray['value']."\n";
			} else {
				$this->formValue .= $propertyArray['label'].":".$propertyArray['value']."\n";
			}
			// << TODO 値の描画機能は各プラグインに持たせる
		}
	
		// 管理者のメールアドレスGET
		$adminEmail = array();
		$adminEmail = explode(",",$this->form_form['receiver_email']);
		
		// メール本文編集
		$this->_getMailBody();
		if($mailToSender == 1){
			// お問い合わせしてきた人にメールを送る
			if($this->toEmail != ''){
				// メールを送る
				$this->_sendMail($this->toEmail,$this->form_form['title'],$this->mailBody,$this->xoopsConfig['sitename'] , $adminEmail[0]);
				$this->sendToSender = true;
			}
		}

		if($mailToReceiver == 1){
			foreach($adminEmail as $val){
				// 管理者にメールを送る
				$this->_sendMail($val,$this->form_form['title'],$this->mailBody,$this->xoopsConfig['sitename'] , $adminEmail[0]);
			}
		}
	}
	
	
	function _sendMail($toEmail, $subject , $body , $fromName , $fromEmail)
	{
		$penginMail = new Pengin_Mail();
		$penginMail->setMailTo($toEmail);
		$penginMail->setSubject($subject);
		$penginMail->setContent($body);
		// from nameはサイト名にしておく
		$penginMail->setMailFrom($fromName , $fromEmail);
		$penginMail->sendMail();
	}
	
	function _getMailBody()
	{
		// Templateファイルの内容を変数に
		$filePath = 'language' . DIRECTORY_SEPARATOR . 'ja'. DIRECTORY_SEPARATOR;
		$fileName = 'mail_body_default.tpl';
		$this->mailBodyTemplate = file_get_contents($filePath.$fileName);
		$this->mailBody = $this->mailBodyTemplate;
		
		// 置換用変数セット
		$this->translateArray = array(
			'SiteName'   => $this->xoopsConfig['sitename'],
			'Name'       => $this->toName,
			'FormTitle'  => $this->form_form['title'],
			'FormValue'  => $this->formValue,
		);
		
		// 文字列置換
		$this->_translateValue();
	}

	protected function _translateValue()
	{
		// <{$xxxxx}>という文字を置き換える（仮）
		$translatePattern = '<{$%s}>';
		
		// 置換用変数を読み込んで置き換え
		foreach($this->translateArray as $key => $val){
			$target = sprintf($translatePattern , $key);
			$this->mailBody = str_replace($target, $val, $this->mailBody);
		}
	}
	
	/**
	 * フォームのトランザクション終了時の処理用メソッド.
	 * 
	 * @access protected
	 * @return void
	 * @note データベースのトランザクションではない
	 */
	protected function _afterTransaction()
	{
		$this->template = 'pen:mailform.form.finish.tpl';
		$this->output['sendToSender'] = $this->sendToSender;
	}

	/**
	 * NiceAdmin タスクバー 連携
	 */
	protected function _adminTaskBar()
	{
		$root = XCube_Root::getSingleton();
		$adminTaskBar = $root->mAdminTaskBar;

		if ( is_object($adminTaskBar) === true ) {
			// adminメニューバー第一階層への表示（モジュール名＋管理）
			$adminTaskBar->addLink('MailformAdmin',  t('Mailform') , '' , 1);

			$url = $this->root->url('form_edit', null, array('id' => $this->id));
			//　パラメータ1　モジュール名（1文字目大文字）＋Admin
			//　パラメータ2　表示方法＋サブのid。表示方法　tpModal：モーダルで出す。tpNoModal:メインに出す。
			//　パラメータ3　サブメニューに表示する名前
			//　パラメータ4　クリックしたときに表示するurl
			$adminTaskBar->addSubLink('MailformAdmin','tpNoModalMailformFormEdit', t("Form Preference"), $url);


			$url = $this->root->url('field_edit', null, array('id' => $this->id));
			//　パラメータ1　モジュール名（1文字目大文字）＋Admin
			//　パラメータ2　表示方法＋サブのid。表示方法　tpModal：モーダルで出す。tpNoModal:メインに出す。
			//　パラメータ3　サブメニューに表示する名前
			//　パラメータ4　クリックしたときに表示するurl
			$adminTaskBar->addSubLink('MailformAdmin','tpNoModalMailformFieldEdit', t("Screen Preference"), $url);
		}
	}

}
