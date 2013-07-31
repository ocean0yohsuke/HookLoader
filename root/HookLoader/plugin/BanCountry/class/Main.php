<?php

class phpBB3_BanCountryMain extends phpBB3_BanCountryBase
{
	private $Client;
	private $IPList = array();
	
	function __construct($Plugin)
	{
		parent::__construct($Plugin);
		$this->Client = new phpBB3_BanCountryClient($Plugin);
		$this->DB = new phpBB3_BanCountryDB($Plugin);
	}
	
	function IP($IPAddress)
	{
		if (!isset($this->IPList[$IPAddress])) {
			$this->IPList[$IPAddress] = new phpBB3_BanCountryIP($IPAddress, $this->DB);
		}
		return $this->IPList[$IPAddress];
	}
	
	function DB()
	{
		return $this->DB;
	}
	
	function isBannedYourself()
	{
		$IPAddressList = $this->Client->IPAddressList();
		foreach ($IPAddressList as $IPAddress)
		{
			if ($this->IP($IPAddress)->isBanned())	{
				return TRUE;
			}
		}
		return FALSE;
	}

	function filter()
	{
		$IPAddressList = $this->Client->IPAddressList();
		foreach ($IPAddressList as $IPAddress)
		{
			if ($this->IP($IPAddress)->isBanned()) {
				$this->display_null();
			}
		}
	}

	private function display_null()
	{
		exit;
	}
}
