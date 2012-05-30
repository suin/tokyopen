<?php

interface SiteNavi_API_SiteNaviInterface
{
	/**
	 * コンテンツ種別名を返す
	 * @return string 種別名 [a-z0-9_]+
	 */
	public function getName();

	/**
	 * コンテンツ種別タイトルを返す
	 * @return string
	 */
	public function getTitle();

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
	public function create(array $data);

	/**
	 * データを削除する
	 * @param array $contentIds コンテンツID
	 * @return bool
	 */
	public function delete(array $contentIds);

	/**
	 * URLからコンテンツIDを返す
	 * @param string $url
	 * @param array $parameters Query String
	 * @return string コンテンツID
	 */
	public function getContentId($url, array $parameters);
}
