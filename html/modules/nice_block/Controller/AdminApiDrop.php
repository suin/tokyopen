<?php
class NiceBlock_Controller_AdminApiDrop extends NiceBlock_Abstract_Controller
{
	protected $blockHandler = null;

	protected $input = array();
	protected $errors = array();

	protected $sides = array(
		NiceBlock_Model_Block::SIDE_LEFT,
		NiceBlock_Model_Block::SIDE_RIGHT,
		NiceBlock_Model_Block::SIDE_CENTER_LEFT,
		NiceBlock_Model_Block::SIDE_CENTER_RIGHT,
		NiceBlock_Model_Block::SIDE_CENTER_CENTER,
	);

	public function __construct()
	{
		parent::__construct();

		$this->blockHandler =& $this->root->getModelHandler('Block');

		$this->input['sides'] = $this->post('sides');
		$this->input['url']   = $this->post('url');
	}

	public function main()
	{
		$this->_default();
	}

	protected function _default()
	{
		try
		{
			$this->_validateInput();
			$this->_savePosition();
		}
		catch ( Exception $e )
		{
			$this->errors[] = $e->getMessage();
		}

		$this->_view();
	}

	protected function _validateInput()
	{
		if ( !is_array($this->input['sides']) )
		{
			throw new Exception(t("Invalid data have been sent."));
		}
	}

	protected function _savePosition()
	{
		$sides = $this->input['sides'];

		foreach ( $sides as $side => $blocks )
		{
			if ( !$this->_isValidSide($side) )
			{
				continue;
			}

			if ( !$this->_isSideHasBlocks($blocks) )
			{
				continue;
			}

			$weight = 1;

			foreach ( $blocks as $blockId )
			{
				$blockModel = $this->blockHandler->load($blockId);
				$blockModel->setVar('weight', $weight);
				$blockModel->setVar('side', $side);
				$result = $this->blockHandler->save($blockModel);

				if ( $result === false )
				{
					throw new Exception(t("Failed to save block position."));
				}

				$weight++;
			}
		}
	}

	protected function _isValidSide($side)
	{
		return in_array($side, $this->sides);
	}

	protected function _isSideHasBlocks($side)
	{
		return ( is_array($side) and count($side) > 0 );
	}

	protected function _view()
	{
		$message = t("Block positions saved successfully.");

		$isError = ( count($this->errors) > 0 );

		if ( $isError )
		{
			$message = implode("\n", $this->errors);
		}

		$result = array(
			'error'   => $isError,
			'message' => $message,
			'url'     => $this->_getReturnUrl(),
		);

		echo json_encode($result);

		die;
	}

	protected function _getReturnUrl()
	{
		$parsed = Pengin_Url::parse($this->input['url']);
		unset($parsed['query']['move_block']);
		return Pengin_Url::glue($parsed);
	}
}
