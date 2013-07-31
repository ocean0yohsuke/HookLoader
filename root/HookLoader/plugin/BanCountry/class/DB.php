<?php

class phpBB3_BanCountryDB extends phpBB3_BanCountryBase
{
	function countryList()
	{
		return isset($this->Plugin->config['countryList'])? explode(',', $this->Plugin->config['countryList']) : array();
	}
	function set_countryList($countryList)
	{
		$list = array();
		foreach($countryList as $country)
		{
			$country = trim($country);
			if ($country) {
				$list[] = $country;
			}
		}
		$this->Plugin->set_config('countryList', implode(",", $list));
	}
	function countryListTypeIsAllow()
	{
		return ($this->Plugin->config['countryListType'] == 1); 
	}
		
}