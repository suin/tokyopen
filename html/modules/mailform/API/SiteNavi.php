<?php

class Mailform_API_SiteNavi implements SiteNavi_API_SiteNaviInterface
{
	const CONTENT_ID_FORMAT = '/mailform/%u/';

	/**
	 * コンテンツ種別名を返す
	 * @return string 種別名 [a-z0-9_]+
	 */
	public function getName()
	{
		return 'mailform';
	}

	/**
	 * コンテンツ種別タイトルを返す
	 * @return string
	 */
	public function getTitle()
	{
		return "メールフォーム";
	}

	/**
	 * データを作成する
	 * @param array $data データ
	 *              string $data['title'] ページタイトル
	 *              string $data['type'] コンテンツ種別名
	 * @return array $dataに以下の要素を追加したものを返す
	 *               string $data['url'] ページのURL
	 *               string $data['content_id'] コンテンツID文字列 /{種別名}/{連番ID}/など
	 *               データの作成に失敗した場合は false を返す
	 */
	public function create(array $data)
	{
		$root = XCube_Root::getSingleton();
		$pengin = Pengin::getInstance();
		$pengin->translator->useTranslation('mailform', $pengin->cms->langcode, 'translation');

		$creator = $root->mContext->mXoopsUser;

		if ( is_object($creator) === true ) {
			$receiverEmail = $creator->get('email');
		}

		$handler = $pengin->getModelHandler('Form', 'mailform');
		$model = $handler->create();
		$model->set('title', $data['title']);
		$model->set('mail_to_sender', 1);
		$model->set('mail_to_receiver', 1);
		$model->set('receiver_email', $receiverEmail);
		$model->set('header_description', t('<p>Please fill out the form below to contact us.</p>'));

		if ( $handler->save($model) === false ) {
			return false;
		}

		$url       = TP_MODULE_URL.'/mailform/index.php?id='.$model->get('id');
		$contentId = sprintf(self::CONTENT_ID_FORMAT, $model->get('id'));

		$data['url'] = $url;
		$data['content_id'] = $contentId;

		// メール, 宛先, 本文はデフォルトで作る
		$pluginManager = new Mailform_Plugin_Manager();
		$defaultFields = array(
			array(
				'type'        => 'Name',
				'label'       => t("Your Name"),
				'description' => '',
				'required'    => 1,
			),
			array(
				'type'        => 'Email',
				'label'       => t("Your Email"),
				'description' => t("Please input your email address exactly."),
				'required'    => 1,
			),
			array(
				'type'        => 'Textarea',
				'label'       => t("Body Text"),
				'description' => '',
				'required'    => 1,
			),
		);

		$fieldHandler = $pengin->getModelHandler('Field', 'mailform');

		$weight = 1;

		foreach ( $defaultFields as $field ) {
			$plugin = $pluginManager->getPlugin($field['type']);
			$fieldModel = $fieldHandler->create();
			$fieldModel->setVars($field);
			$fieldModel->set('form_id', $model->get('id'));
			$fieldModel->set('options', $plugin->getDefaultPluginOptions());
			$fieldModel->set('weight', $weight);

			if ( $fieldHandler->save($fieldModel) === false ) {
				return false;
			}

			if ( $fieldHandler->autoUpdateName($fieldModel) === false ) {
				return false;
			}
			
			$weight += 1;
		}

		return $data;
	}

	/**
	 * データを削除する
	 * @param array $contentIds コンテンツID
	 * @return bool
	 */
	public function delete(array $contentIds)
	{
		$ids = array();

		foreach ( $contentIds as $contentId ) {
			if ( preg_match('#^/mailform/(?P<id>[0-9]+)/$#', $contentId, $matches) == 0 ) {
				continue;
			}

			$ids[] = $matches['id'];
		}

		$pengin  = Pengin::getInstance();

		$fieldHandler = $pengin->getModelHandler('Field', 'mailform');
		$criteria = new Pengin_Criteria();
		$criteria->add('form_id', 'IN', $ids);

		if ( $fieldHandler->deleteAll($criteria) == false ) {
			return false;
		}

		$formHandler = $pengin->getModelHandler('Form', 'mailform');
		$criteria = new Pengin_Criteria();
		$criteria->add('id', 'IN', $ids);

		if ( $formHandler->deleteAll($criteria) == false ) {
			return false;
		}

		return $result;
	}

	/**
	 * URLからコンテンツIDを返す
	 * @param string $url
	 * @param array $parameters Query String
	 * @return string コンテンツID
	 */
	public function getContentId($url, array $parameters)
	{
		if ( array_key_exists('id', $parameters) === false ) {
			return false;
		}

		$contentId = sprintf(self::CONTENT_ID_FORMAT, $parameters['id']);
		return $contentId;
	}
}
