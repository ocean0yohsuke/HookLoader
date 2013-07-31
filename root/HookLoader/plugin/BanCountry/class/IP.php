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
		$this->IPtoCountry = new IPtoCountry(PHPBB_HOOKLOADER_BANCOUNTRY_ROOT_PATH . 'include/IPtoCountry/IPtoCountry.db');
	}
	
	function isBanned()
	{
		$countryListTypeIsAllow 	= $this->DB->countryListTypeIsAllow();
		$countryList 	= $this->DB->countryList();
		$country 		= $this->IPtoCountry->toCountry($this->IPAddress);

		if ((!$countryListTypeIsAllow && in_array($country, $countryList))
		|| ($countryListTypeIsAllow && !in_array($country, $countryList)))
		{
			//return $this->isException();
			return TRUE;
		}

		return FALSE;
	}	
	
	/*
	function isException()
	{
		$exceptionIPList = $this->DB->exceptionIPList();
		foreach($exceptionIPList as $IPAddress)
		{
			$isException = preg_match('#^' . str_replace('\*', '.*?', preg_quote($IPAddress, '#')) . '$#i', $this->IPAddress);
			if ($isException) {
				return TRUE;
			}
		}
		return FALSE;
	}
	*/
}
