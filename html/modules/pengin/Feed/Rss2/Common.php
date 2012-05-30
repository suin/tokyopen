<?php
abstract class Pengin_Feed_Rss2_Common extends Pengin_Xml_Proxy
{
	/**
	 * <channel>
	 * The name of the channel. It's how people refer to your service. If you have 
	 * an HTML website that contains the same information as your RSS file, the 
	 * title of your channel should be the same as the title of your website.
	 * 
	 * <item>
	 * The title of the item.
	 */
	public function setTitle($title)
	{
		return $this->setChild('title', $title);
	}

	/**
	 * <channel>
	 * The URL to the HTML website corresponding to the channel.
	 *
	 * <item>
	 * The URL of the item.
	 */
	public function setLink($url)
	{
		return $this->setChild('link', $url);
	}

	/**
	 * <channel>
	 * Phrase or sentence describing the channel.
	 *
	 * <item>
	 * The item synopsis.
	 */
	public function setDescription($description)
	{
		return $this->setChild('description', $description);
	}

	/**
	 * <channel>
	 * Specify one or more categories that the channel belongs to. Follows the same 
	 * rules as the <item>-level category element.
	 *
	 * <item>
	 * Includes the item in one or more categories.
	 */
	public function addCategory($name, $domain = null)
	{
		$category = $this->addChild('category', $name);

		if ( $domain !== null )
		{
			$category->addAttribute('domain', $domain);
		}

		return $category;
	}

	protected function _setContact($element, $email, $name = null)
	{
		if ( $name !== null )
		{
			$email .= ' ('.$name.')';
		}

		return $this->setChild($element, $email);
	}

	protected function _setDatetime($element, $timestamp)
	{
		$datetime = date(DATE_RSS, $timestamp);
		return $this->setChild($element, $datetime);
	}
}
