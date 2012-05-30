<?php
class Pengin_Mail
{
	protected $head    = array();
	protected $mailTo  = '';
	protected $subject = '';
	protected $content = '';
	protected $parameter = null;

	public function __construct()
	{
		mb_language('Ja');
		mb_internal_encoding('UTF-8');
		$this->_initializeHead();
	}

	public function setMailTo($email)
	{
		$this->mailTo = $email;
	}

	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function setMailFrom($name, $email)
	{
		$this->addHead('From', sprintf('%s <%s>', mb_encode_mimeheader($name), $email));
		$this->addHead('Reply-To', sprintf('%s <%s>', mb_encode_mimeheader($name), $email));
		$this->parameter = "-f $email";
	}

	public function setBCC($email)
	{
		$this->addHead('Bcc', $email);
	}

	public function addHead($name, $value)
	{
		$this->head[] = sprintf('%s: %s ', $name, $value);
	}

	public function sendMail()
	{
		return @mb_send_mail($this->mailTo, $this->subject, $this->content, $this->_getHeader(), $this->parameter);
	}
	
	protected function _initializeHead()
	{
		$this->head = array();
	}

	protected function _getHeader()
	{
		return implode("\n", $this->head);
	}
}

