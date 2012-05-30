<?php

class SiteNavi_Controller_AdminPageMove extends SiteNavi_Abstract_ThreeStepAjaxFormController
{
	protected function _setUpModel()
	{
		// do nothing.
	}

	protected function _setUpForm()
	{
		$this->form = $this->_getForm();
	}

	protected function _getModelHandler()
	{
		return $this->root->getModelHandler('Route');
	}

	protected function _getForm()
	{
		return new SiteNavi_Form_AdminPageMove();
	}

	protected function _updateData()
	{
		$sourceId = $this->form->property('source_id')->getValue();
		$targetId = $this->form->property('target_id')->getValue();
		$position = $this->form->property('position')->getValue();

		/** @var SiteNavi_Model_RouteHandler $routeHandler */
		$routeHandler = $this->root->getModelHandler('Route');

		/** @var SiteNavi_Model_Route $source */
		/** @var SiteNavi_Model_Route $target */
		$movingPage = $routeHandler->load($sourceId); // 動かすページ
		$targetPage = $routeHandler->load($targetId); // 移動先基準となるページ

		if ( $position === 'into' ) {
			// フォルダに放り込むだけ
			if ( $routeHandler->move($movingPage, $targetPage, $reason) === false ) {
				throw new RuntimeException($reason);
			}
		} else {
			// before か after

			if ( $routeHandler->isInSameFolder($movingPage, $targetPage) === false ) {
				// パス移動する場合
				$targetFolder = $routeHandler->load($targetPage->get('parent_id'));

				// 一度、フォルダに放り込む
				if ( $routeHandler->move($movingPage, $targetFolder, $reason) === false ) {
					throw new RuntimeException($reason);
				}
			}

			// 並び順変更
			if ( $routeHandler->sort($movingPage, $targetPage, $position, $reason) === false ) {
				throw new RuntimeException($reason);
			}
		}
	}
}
