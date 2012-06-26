<?php

class Mailform_Controller_FieldEdit extends Mailform_Abstract_Controller
{
	protected $useModels = array('Form', 'Field');

	protected $formId = 0;
	protected $formModel = null;
	protected $filedModels = array();

	protected $input = array();
	protected $errors = array();

	public function main()
	{
		$this->_checkPermsission();
		$this->_fetchFormId();
		$this->_setUpFormModel();
		$this->_setUpFieldMdoels();
		$this->_setUpInput();

		if ( $this->post('save') ) {
			$this->_saveAction();
		}
		
		$this->_adminTaskBar();

		$this->_defaultAction();
	}

	protected function _checkPermsission()
	{
		if ( $this->root->cms->isAdmin() === false ) {
			die('error');
		}
	}

	protected function _fetchFormId()
	{
		$this->formId = $this->get('id');

		if ( $this->formId < 1 ) {
			die('error');
		}
	}

	protected function _setUpFormModel()
	{
		$formModel = $this->formHandler->load($this->formId);

		if ( is_object($formModel) === false ) {
			die('error');
		}

		$this->formModel = $formModel;
	}

	protected function _setUpFieldMdoels()
	{
		$this->fieldModels = $this->fieldHandler->findByFormId($this->formId);
	}

	protected function _setUpInput()
	{
		$this->input = array(
			'title' => $this->formModel->get('title'),
			'header_description' => $this->formModel->get('header_description'),
			'fields' => array(),
		);

		foreach ( $this->fieldModels as $fieldModel ) {
			$this->input['fields'][] = array(
				'id'       => $fieldModel->get('id'),
				'label'    => $fieldModel->get('label'),
				'type'     => $fieldModel->get('type'),
				'required' => $fieldModel->get('required'),
				'description' => $fieldModel->get('description'),
				'options'  => $fieldModel->get('options'),
			);
		}
	}

	protected function _defaultAction()
	{
		$this->output['input'] = $this->input;
		$this->output['errors'] = $this->errors;
		$this->output['formData'] = json_encode($this->input);
		$this->output['pluginInfo'] = $this->_getPluginInfo();
		$this->_view();
	}

	protected function _saveAction()
	{
		$this->_fetchInput();
		$this->_validate();

		if ( count($this->errors) == 0 ) {
			if ( $this->_save() === true ) {
				$url = $this->url.'/index.php?id='.$this->formId;
				$this->root->redirect("Successfully saved.", $url);
			}
		}
	}

	protected function _fetchInput()
	{
		$input = array();
		$input['title'] = $this->post('title');
		$input['header_description'] = $this->post('header_description');
		$input['fields'] = $this->_fetchInputFields();
		$this->input = $input;
	}

	protected function _fetchInputFields()
	{
		$defaultField = array(
			'id'       => 0,
			'label'    => '',
			'type'     => '',
			'required' => 0,
			'options'  => array(),
		);

		$fields = $this->post('fields');

		if ( is_array($fields) === false ) {
			return array();
		}

		if ( count($fields) === 0 ) {
			return array();
		}

		foreach ( $fields as $key => $field ) {
			$fields[$key] = array_merge($defaultField, $field);
		}

		return $fields;
	}

	protected function _validate()
	{
		$this->_validateTitle();
		$this->_validateNameField();
		$this->_validateEmailField();
		$this->_validateFields();
	}

	protected function _validateTitle()
	{
		if ( $this->input['title'] == '' ) {
			$this->errors[] = t("Please enter {1}.", t("Form Title"));
		}
	}

	protected function _validateNameField()
	{
		$fieldCount = 0;

		foreach ( $this->input['fields'] as $field ) {
			if ( $field['type'] === 'Name' ) {
				$fieldCount += 1;
			}
		}

		if ( $fieldCount < 1 ) {
			$this->errors[] = t("Please place one {1} field.", t("Sender Name"));
		}

		if ( $fieldCount > 1 ) {
			$this->errors[] = t("{1} field must be one. {2} fields were placed.", t("Sender Name"), $fieldCount);
		}
	}

	protected function _validateEmailField()
	{
		$fieldCount = 0;

		foreach ( $this->input['fields'] as $field ) {
			if ( $field['type'] === 'Email' ) {
				$fieldCount += 1;
			}
		}

		if ( $fieldCount < 1 ) {
			$this->errors[] = t("Please place one {1} field.", t("Email"));
		}

		if ( $fieldCount > 1 ) {
			$this->errors[] = t("{1} field must be one. {2} fields were placed.", t("Email"), $fieldCount);
		}
	}

	protected function _validateFields()
	{
		if ( count($this->input['fields']) === 0 ) {
			$this->errors[] = t("Please create at least one field.");
			return;
		}

		$pluginManager = new Mailform_Plugin_Manager();

		$row = 1;

		foreach ( $this->input['fields'] as $field ) {

			// 空行は飛ばす
			if ( $field['label'] == '' and $field['type'] == '' and $field['description'] == '' ) {
				continue;
			}

			// 各行の呼び名を決める
			if ( $field['label'] == '' ) {
				$name = t("Row {1}", $row);
			} else {
				$name = $field['label'];
			}

			// 入力欄名のバリエーション
			if ( $field['label'] == '' ) {
				$this->errors[] = $name .': '. t("Please enter {1}.", t("Label"));
			}

			// 入力欄有無のバリエーション
			if ( $field['type'] == '' ) {
				$this->errors[] = $name .': '. t("Please place a field.");
			} else {
				$plugin = $pluginManager->getPlugin($field['type']);
				
				if ( $plugin == false ) {
					$this->errors[] = $name.': '.t("Unexpected field.");
				} else {
					// プラグイン固有のバリエーション
					$errors = $plugin->validatePluginOptions($field['options']);

					foreach ( $errors as $error ) {
						$this->errors[] = $name.': '.$error;
					}
				}
			}

			// 必須フラグのチェック: メールと宛名は絶対必須でないといけない
			if ( $field['type'] === 'Email' or $field['type'] === 'Name' ) {
				if ( $field['required'] == 0 ) {
					$this->errors[] = $name .': '.t("Requiring setting must be on.");
				}
			}

			$row += 1;
		}
	}

	protected function _save()
	{
		$db = $this->root->cms->database();

		try {
			$db->query('BEGIN');
			$this->_saveFormData();
			$this->_deleteFields();
			$this->_saveFields();
			$this->_notifyUpdateToSiteNavi();
			$db->query('COMMIT');
		} catch ( Exception $e ) {
			$db->query('ROLLBACK');
			$this->errors[] = $e->getMessage();
			return false;
		}

		return true;
	}

	protected function _saveFormData()
	{
		$formData = $this->input;
		unset($formData['fields']);
		$this->formModel->setVars($formData);

		$saved = $this->formHandler->save($this->formModel);

		if ( $saved === false ) {
			throw new Exception(t("Failed to save form data."));
		}
	}

	/**
	 * 取り除かれたフィールドを削除する
	 */
	protected function _deleteFields()
	{
		$fieldsData = $this->input['fields'];

		$newIds = array();
		$oldIds = array();

		foreach ( $fieldsData as $fieldData ) {
			$newIds[] = $fieldData['id'];
		}

		foreach ( $this->fieldModels as $fieldModel ) {
			$oldIds[] = $fieldModel->get('id');
		}

		$deleteIds = array_diff($oldIds, $newIds);

		$deleted = $this->fieldHandler->deleteAllByIds($deleteIds);

		if ( $deleted === false ) {
			throw new Exception(t("Failed to delete old fields."));
		}
	}

	protected function _saveFields()
	{
		$fieldsData = $this->input['fields'];
		$weight = 1;

		foreach ( $fieldsData as $fieldData ) {

			if ( $fieldData['label'] == '' and $fieldData['type'] == '' and $fieldData['description'] == '' ) {
				continue;
			}

			$id = $fieldData['id'];
			unset($fieldData['id']);
			
			if ( $id == 0 ) {
				// 新規作成
				$fieldModel = $this->fieldHandler->create();
				$fieldModel->set('form_id', $this->formId);
			} else {
				// 更新
				if ( isset($this->fieldModels[$id]) === false ) {
					throw new Exception(t("Field data not found: id: {1}", $id));
				}
				
				$fieldModel = $this->fieldModels[$id];
			}
			
			$fieldModel->setVars($fieldData);
			$fieldModel->setVar('weight', $weight);

			$saved = $this->fieldHandler->save($fieldModel);

			if ( $saved === false ) {
				throw new Exception(t("Failed to save field data: id: {1}", $id));
			}

			$updated = $this->fieldHandler->autoUpdateName($fieldModel);
			
			if ( $updated === false ) {
				throw new Exception(t("Failed to update field name: id: {1}", $fieldModel->get('id')));
			}
			
			$weight += 1;
		}
	}

	protected function _getPluginInfo()
	{
		$pluginManager = new Mailform_Plugin_Manager();
		$pluginInfo = $pluginManager->getPluginInfo();
		$pluginInfo = json_encode($pluginInfo);
		return $pluginInfo;
	}

	protected function _notifyUpdateToSiteNavi()
	{
		// TODO >> この部分は モジュールメディエイター を作って移植する
		// refs #7403
		$pengin = Pengin::getInstance();
		$pengin->path(TP_MODULE_PATH.'/site_navi');
		
		if ( class_exists('SiteNavi_API_Page') === false ) {
			return;
		}

		$contentId = sprintf('/mailform/%u/', $this->formModel->get('id'));
		$title     = $this->formModel->get('title');

		$page = new SiteNavi_API_Page();
		$isSuccess = $page->updateTitle($contentId, $title);
		return $isSuccess;
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

			$url = $this->root->url('form_edit', null, array('id' => $this->formModel->get('id')));
			//　パラメータ1　モジュール名（1文字目大文字）＋Admin
			//　パラメータ2　表示方法＋サブのid。表示方法　tpModal：モーダルで出す。tpNoModal:メインに出す。
			//　パラメータ3　サブメニューに表示する名前
			//　パラメータ4　クリックしたときに表示するurl
			$adminTaskBar->addSubLink('MailformAdmin','tpNoModalMailformFormEdit', t("Form Preference"), $url);


			$url = $this->root->url('field_edit', null, array('id' => $this->formModel->get('id')));
			//　パラメータ1　モジュール名（1文字目大文字）＋Admin
			//　パラメータ2　表示方法＋サブのid。表示方法　tpModal：モーダルで出す。tpNoModal:メインに出す。
			//　パラメータ3　サブメニューに表示する名前
			//　パラメータ4　クリックしたときに表示するurl
			$adminTaskBar->addSubLink('MailformAdmin','tpNoModalMailformFieldEdit', t("Screen Preference"), $url);
		}
	}

	
}
