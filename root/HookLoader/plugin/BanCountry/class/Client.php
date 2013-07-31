<?php

class phpBB3_BanCountryClient extends phpBB3_BanCountryBase
{
	private $clientIP;
	private $proxyIP;

	function __construct($Plugin)
	{
		parent::__construct($Plugin);
		$this->setIP();
	}
	
	private function setIP()
	{
		if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
				$proxy = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$proxy = $_SERVER["REMOTE_ADDR"];
			}
			$client = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
				$client = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$client = $_SERVER["REMOTE_ADDR"];
			}
		}
		$this->proxyIP = (isset($proxy))? $proxy : null;
		$this->clientIP = $client;
	}
		
	function clientIP()
	{
		$return = (isset($this->clientIP))? $this->clientIP : false;
		return $return;
	}

	function proxyIP()
	{
		$return = (isset($this->proxyIP))? $this->proxyIP : false;
		return $return;
	}
	
	function IPAddressList()
	{
		$list = array();

		$list = array($this->clientIP());
		if ($proxyIP = $this->proxyIP()) {
			$list[] = $proxyIP;
		}

		global $user;
		if (isset($user))	{
			if (!empty($user->ip)) {
				// IP address user is currently browsing under
				$list[] = $user->ip;
			}
			if (!empty($user->data['user_ip']) && $this->Plugin->config['checkRegistrationIP']) {
				// IP user registered with NOT CURRENT BROWSING IP
				$list[] = $user->data['user_ip'];
			}
		}

		return array_unique($list);
	}	
}