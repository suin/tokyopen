<?php
class Footer_Model_MenuHandler extends Pengin_Model_AbstractHandler
{
	public function insertMenus($item)
	{

		$titles = explode(',',substr($item['titles'], 0, -1));
		$urls = explode(',',substr($item['urls'], 0, -1));
		$i = 0;
		foreach($titles as $value){
			$model = $this->create();
			$model->set('title', $titles[$i]);
			$model->set('url', $urls[$i]);
			$model->set('weight',$i);
			if($this->save($model) == false){
				return false;
			}
			$i++;
		}
		return true;
	}
}
