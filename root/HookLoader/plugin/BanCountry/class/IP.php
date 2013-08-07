<?php

class phpBB3_BanCountryIP
{
	private $IPAddress;
	private $DB;
	private $IPtoCountry;
	
	function __construct($IPAddress, $DB)
	{
		$this->IPAddress = $IPAddress;
		$this->DB = $DB;

		include_once PHPBB_HOOKLOADER_BANCOUNTRY_ROOT_PATH . 'include/IPtoCountry/IPtoCountry.php';
		try {
			$this->IPtoCountry = new IPtoCountry(PHPBB_HOOKLOADER_BANCOUNTRY_ROOT_PATH . 'include/IPtoCountry/IPtoCountry.db');
		}
		catch (IPtoCountryException $e) {
			throw new phpBB3_BanCountryException($e->getMessage());
		}
	}
	
	function isBanned()
	{
		$countryListTypeIsAllow 	= $this->DB->countryListTypeIsAllow();
		$countryList 	= $this->DB->countryList();
		try {
			$country 		= $this->IPtoCountry->toCountry($this->IPAddress);
		}
		catch (IPtoCountryException $e) {
			throw new phpBB3_BanCountryException($e->getMessage());
		}

		if ((!$countryListTypeIsAllow && in_array($country, $countryList))
		|| ($countryListTypeIsAllow && !in_array($country, $countryList)))
		{
			//return $this->isExcepted();
			return TRUE;
		}

		return FALSE;
	}	
	
	/*
	function isExcepted()
	{
		$exceptIPList = $this->DB->exceptIPList();
		foreach($exceptIPList as $IPAddress)
		{
			$isExcepted = preg_match('#^' . str_replace('\*', '.*?', preg_quote($IPAddress, '#')) . '$#i', $this->IPAddress);
			if ($isExcepted) {
				return TRUE;
			}
		}
		return FALSE;
	}
	*/
}
