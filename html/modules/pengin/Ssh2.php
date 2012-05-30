<?php
class Pengin_Ssh2
{
	protected $connection = null;
	protected $logs = array();

	public function __construct()
	{
	}

	public function connect($host, $port = 22, $methods = array(), $callbacks = array())
	{
		$connection = ssh2_connect($host, $port, $methods, $callbacks);

		if ( !$connection )
		{
			$this->logs[] = "Connection failed";
			return false;
		}

		$this->connection = $connection;

		return true;
	}

	public function authPassword($user, $password)
	{
		if ( !ssh2_auth_password($this->connection, $user, $password) )
		{
			$this->logs[] = "Password Authorization failed";
			return false;
		}

		return true;
	}

	public function authPubkey($user, $pubkeyFile, $privkeyFile, $passphrase)
	{
		if ( !ssh2_auth_pubkey_file($this->connection, $user, $pubkeyFile, $privkeyFile, $passphrase) )
		{
			$this->logs[] = "Public Key Authorization failed";
			return false;
		}

		return true;
	}

	public function command($command)
	{
		$stream = ssh2_exec($this->connection, $command);
		$this->logs[] = '$ '.$command;

		stream_set_blocking($stream, true);
		$d = fread($stream, 4096);
		fclose($stream);
		$this->logs[] = $d;

		return $d;
	}

	public function scpSend($localFile, $remoteFile, $createMode = 0644)
	{
		$this->logs[] = '$ scp '.$localFile.' remote:'.$remoteFile;
		$result = ssh2_scp_send($this->connection, $localFile, $remoteFile, $createMode);

		if ( !$result )
		{
			$this->logs[] = 'Send a file via SCP failed.';
			return false;
		}

		return true;
	}

	public function disconnect()
	{
		$this->command('exit');
	}

	public function getLogs()
	{
		return $this->logs;
	}

	public function getLastLog()
	{
		return end($this->logs);
	}
}

?>
