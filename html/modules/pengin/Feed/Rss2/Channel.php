<?php
class Pengin_Feed_Rss2_Channel extends Pengin_Feed_Rss2_Common
{
	const IMAGE_WIDTH_MAX  = 144;
	const IMAGE_HEIGHT_MAX = 400;

	protected $rootElement = '<channel />';

	public function __construct($title, $link, $description)
	{
		parent::__construct();
		$this->setTitle($title);
		$this->setLink($link);
		$this->setDescription($description);
	}

	/*
	 * The language the channel is written in. This allows aggregators to group all
	 * Italian language sites, for example, on a single page. A list of allowable 
	 * values for this element, as provided by Netscape, is here. You may also use 
	 * values defined by the W3C.
	 */
	public function setLanguage($language)
	{
		return $this->setChild('language', $language);
	}

	/*
	 * Copyright notice for content in the channel.
	 */
	public function setCopyright($copyright)
	{
		return $this->setChild('copyright', $copyright);
	}

	/*
	 * Email address for person responsible for editorial content.
	 */
	public function setManagingEditor($email, $name = null)
	{
		return $this->_setContact('managingEditor', $email, $name);
	}

	/*
	 * Email address for person responsible for technical issues relating to channel.
	 */
	public function setWebMaster($email, $name = null)
	{
		return $this->_setContact('webMaster', $email, $name);
	}

	/**
	 * The publication date for the content in the channel. For example, the New 
	 * York Times publishes on a daily basis, the publication date flips once every 
	 * 24 hours. That's when the pubDate of the channel changes. All date-times in 
	 * RSS conform to the Date and Time Specification of RFC 822, with the exception 
	 * that the year may be expressed with two characters or four characters (four 
	 * preferred).
	 */
	public function setPubDate($timestamp)
	{
		return $this->_setDatetime('pubDate', $timestamp);
	}

	/**
	 * The last time the content of the channel changed.
	 */
	public function setLastBuildDate($timestamp)
	{
		return $this->_setDatetime('lastBuildDate', $timestamp);
	}

	/**
	 * A string indicating the program used to generate the channel.
	 */
	public function setGenerator($generator)
	{
		return $this->setChild('generator', $generator);
	}

	/**
	 * A URL that points to the documentation for the format used in the RSS file. 
	 * It's probably a pointer to this page. It's for people who might stumble 
	 * across an RSS file on a Web server 25 years from now and wonder what it is.
	 */
	public function setDocs($url)
	{
		return $this->setChild('docs', $url);
	}

	/**
	 * Allows processes to register with a cloud to be notified of updates to the 
	 * channel, implementing a lightweight publish-subscribe protocol for RSS feeds.
	 */
	public function setCloud()
	{
		// not yet
	}

	/**
	 * ttl stands for time to live. It's a number of minutes that indicates how long 
	 * a channel can be cached before refreshing from the source.
	 */
	public function setTtl($minutes)
	{
		return $this->setChild('ttl', $minutes);
	}

	/**
	 * Specifies a GIF, JPEG or PNG image that can be displayed with the channel.
	 */
	public function setImage($imageUrl, $imageTitle, $siteUrl, $width = null, $height = null, $description = null)
	{
		$image = $this->setChild('image', null);

		$requiredElements = array('url', 'title', 'link');

		foreach ( $requiredElements as $element )
		{
			if ( isset($image->$element) === false )
			{
				$image->addChild($element);
			}
		}

		$image->url   = $imageUrl;
		$image->title = $imageTitle;
		$image->link  = $siteUrl;

		$width  = intval($width);
		$height = intval($height);

		if ( $width > 0 and $height > 0 )
		{
			if ( $width > self::IMAGE_WIDTH_MAX )
			{
				$width = self::IMAGE_WIDTH_MAX;
			}

			if ( $height > self::IMAGE_HEIGHT_MAX )
			{
				$height = self::IMAGE_HEIGHT_MAX;
			}

			$image->width  = $width;
			$image->height = $height;
		}

		if ( $description !== null )
		{
			$image->description = $description;
		}
		
		return $image;
	}

	/**
	 * The PICS rating for the channel.
	 */
	public function setRating($rating)
	{
		return $this->setChild($rating);
	}

	/**
	 * Specifies a text input box that can be displayed with the channel.
	 */
	public function setTextInput()
	{
		// not yet
	}

	/**
	 * A hint for aggregators telling them which hours they can skip.
	 */
	public function addSkipHours()
	{
		// not yet
	}

	/**
	 * A hint for aggregators telling them which days they can skip.
	 */
	public function setSkipDays()
	{
		// not yet
	}

	public function addItem(Pengin_Feed_Rss2_Item $item)
	{
		return $this->addChildXML($item);
	}
}
