<?php
class Pengin_Pager
{
	protected $current = 0;
	protected $perPage = 0;
	protected $total   = 0;

	protected $firstPage   = 0;
	protected $currentPage = 0;
	protected $totalPage   = 0;
	protected $indexOffset = 3;

	protected $firstLabel    = "最初"; // TODO >> 国際化
	protected $previousLabel = "前";
	protected $nextLabel     = "次";
	protected $lastLabel     = "最後";

	protected $url = null;

	protected $separator = ' | ';
	protected $startName = 'start';

	/** For example :
		$pager = new Pengin_Pager(array(
			'current' => 0, 
			'perPage' => 5, 
			'total'   => $total, 
		));
	 */

	public function __construct($param)
	{
		$this->current = intval($param['current']);
		$this->perPage = intval($param['perPage']);
		$this->total   = intval($param['total']);

		if ( $param['perPage'] == 0 )
		{
			$param['perPage'] = 1;
		}

		$this->currentPage = intval(floor($param['current'] / $param['perPage']));
		$this->totalPage   = intval(ceil($param['total'] / $param['perPage']));

		$this->url = $_SERVER['REQUEST_URI'];

		$optionals = array(
			'indexOffset',
			'firstLabel',
			'previousLabel',
			'nextLabel',
			'lastLabel',
			'separator',
			'startName',
			'url',
		);

		foreach ( $optionals as $optional )
		{
			if ( isset($param[$optional]) )
			{
				$this->$optional = $param[$optional];
			}
		}

		$this->url = $this->_getUrl();
	}

	public function getPages()
	{
		$pages = array();

		$pages[] = array(
			'label'   => $this->firstLabel,
			'name'    => 'first',
			'start'   => $this->_getFirstPage() * $this->perPage,
			'is_link' => $this->_isAbleLeftLinker(),
			'url'     => $this->_getUrlWithPage($this->_getFirstPage() * $this->perPage),
		);

		$pages[] = array(
			'label'   => $this->previousLabel,
			'name'    => 'previous',
			'start'   => $this->_getPreviousPage() * $this->perPage,
			'is_link' => $this->_isAbleLeftLinker(),
			'url'     => $this->_getUrlWithPage($this->_getPreviousPage() * $this->perPage),
		);

		$indexPages = $this->_getIndex();

		foreach( $indexPages as $indexPage )
		{
			$pages[] = array(
				'label'   => $indexPage + 1,
				'name'    => ( $this->_getCurrentPage() == $indexPage ) ? 'current' : 'index',
				'start'   => $indexPage * $this->perPage,
				'is_link' => ( $this->_getCurrentPage() != $indexPage ),
				'url'     => $this->_getUrlWithPage($indexPage * $this->perPage),
			);
		}

		$pages[] = array(
			'label'   => $this->nextLabel,
			'name'    => 'next',
			'start'   => $this->_getNextPage() * $this->perPage,
			'is_link' => $this->_isAbleRightLinker(),
			'url'     => $this->_getUrlWithPage( $this->_getNextPage() * $this->perPage),
		);

		$pages[] = array(
			'label'   => $this->lastLabel,
			'name'    => 'last',
			'start'   => $this->_getLastPage() * $this->perPage,
			'is_link' => $this->_isAbleRightLinker(),
			'url'     => $this->_getUrlWithPage( $this->_getLastPage() * $this->perPage),
		);

		return $pages;
	}

	protected function _getFirstPage()
	{
		return $this->firstPage;
	}

	protected function _getPreviousPage()
	{
		if ( $this->currentPage <= $this->firstPage )
		{
			return $this->currentPage;
		}

		return $this->currentPage - 1;
	}

	protected function _getTotalPage()
	{
		return $this->totalPage;
	}

	protected function _getNextPage()
	{
		if ( $this->currentPage >= $this->totalPage )
		{
			return $this->currentPage;
		}

		return $this->currentPage + 1;
	}

	protected function _getLastPage()
	{
		return $this->totalPage - 1;
	}

	protected function _getCurrentPage()
	{
		return $this->currentPage;
	}

	protected function _getIndex()
	{
		$ret = array();
		$leftLack  = $this->indexOffset - abs($this->firstPage - $this->currentPage);
		$rightLack = $this->indexOffset - abs($this->totalPage - $this->currentPage);

		if ( $leftLack > 0 )
		{
			$offset = $this->indexOffset + $leftLack;
		}
		elseif ( $rightLack > 0 )
		{
			$offset = $this->indexOffset + $rightLack;
		}
		else
		{
			$offset = $this->indexOffset;
		}

		for ( $page = $this->firstPage; $page < $this->totalPage; $page++ )
		{
			if ( abs($page - $this->currentPage) <= $offset )
			{
				$ret[] = $page;
			}
		}

		return $ret;
	}

	protected function _getUrlWithPage($page)
	{
		if ( parse_url($this->url, PHP_URL_QUERY) )
		{
			return $this->url.'&'.$this->startName.'='.intval($page);
		}
		else
		{
			return $this->url.'?'.$this->startName.'='.intval($page);
		}
	}

	protected function _isAbleLeftLinker()
	{
		return ( $this->totalPage >= 2 and $this->currentPage - $this->firstPage >= 1 );
	}

	protected function _isAbleRightLinker()
	{
		return ( $this->totalPage >= 2 and $this->totalPage  - $this->currentPage >= 2 );
	}

	protected function _getUrl()
	{
		$url = $this->url;
		$parts = explode('?', $url);

		if ( isset($parts[1]) )
		{
			parse_str($parts[1], $params);
			unset($parts[1]);
			unset($params[$this->startName]);
			$parts[1] = http_build_query($params);

			if ( $parts[1] === '' )
			{
				unset($parts[1]);
			}
		}

		$url = implode('?', $parts);

		return $url;
	}
}

?>
