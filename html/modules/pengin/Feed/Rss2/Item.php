<?php
class Pengin_Feed_Rss2_Item extends Pengin_Feed_Rss2_Common
{
	protected $rootElement = '<item />';

	public function __construct($title, $link, $description)
	{
		parent::__construct();
		$this->setTitle($title);
		$this->setLink($link);
		$this->setDescription($description);
	}

	/**
	 * Email address of the author of the item.
	 */
	public function setAuthor($email, $name = null)
	{
		return $this->_setContact('author', $email, $name);
	}

	/**
	 * URL of a page for comments relating to the item.
	 */
	public function setComments($url)
	{
		return $this->setChild('comments', $url);
	}

	/**
	 * Describes a media object that is attached to the item.
	 */
	public function setEnclosure($url, $length = null, $type = null)
	{
		$enclosure = $this->setChild('enclosure');
		$enclosure->addAttribute('url', $url);

		if ( $length !== null )
		{
			$enclosure->addAttribute('length', $length);
		}

		if ( $type !== null )
		{
			$enclosure->addAttribute('type', $type);
		}

		return $enclosure;
	}

	/**
	 * A string that uniquely identifies the item.
	 */
	public function setGuid($guid, $isPermaLink = true)
	{
		$guid = $this->setChild('guid', $guid);

		if ( $isPermaLink )
		{
			$isPermaLink = 'true';
		}
		else
		{
			$isPermaLink = 'false';
		}

		$guid->addAttribute('isPermaLink', $isPermaLink);
		return $guid;
	}

	/**
	 * Indicates when the item was published.
	 */
	public function setPubDate($timestamp)
	{
		return $this->_setDatetime('pubDate', $timestamp);
	}

	/**
	 * The RSS channel that the item came from.
	 */
	public function setSource($url, $title)
	{
		$source = $this->setChild('source', $title);
		$source->addAttribute('url', $url);
		return $source;
	}
}
