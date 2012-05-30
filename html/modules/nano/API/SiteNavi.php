<?php

class Nano_API_SiteNavi implements SiteNavi_API_SiteNaviInterface
{
	const CONTENT_ID_FORMAT = '/nano/%u/';

	public function getName()
	{
		return 'nano';
	}

	public function getTitle()
	{
		return "静的ページ";
	}

	/**
	 * データを作成する
	 * @param array $data データ
	 * @return array 失敗したらFALSE
	 */
	public function create(array $data)
	{
		$pengin = Pengin::getInstance();
		$handler = $pengin->getModelHandler('Contents', 'nano');
		$model = $handler->create();
		$model->set('title', $data['title']);
		
		if ( $handler->save($model) === false ) {
			return false;
		}

		$url       = TP_MODULE_URL.'/nano/index.php?id='.$model->get('id');
		$contentId = sprintf(self::CONTENT_ID_FORMAT, $model->get('id'));

		$data['url'] = $url;
		$data['content_id'] = $contentId;

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
			if ( preg_match('#^/nano/(?P<id>[0-9]+)/$#', $contentId, $matches) == 0 ) {
				continue;
			}

			$ids[] = $matches['id'];
		}

		$criteria = new Pengin_Criteria();
		$criteria->add('id', 'IN', $ids);

		$pengin  = Pengin::getInstance();
		$handler = $pengin->getModelHandler('Contents', 'nano');
		$result  = $handler->deleteAll($criteria);
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
