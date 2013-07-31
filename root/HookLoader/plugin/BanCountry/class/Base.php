<?php

class phpBB3_BanCountryBase
{
	protected $Plugin;
	
	function __construct(phpBB3_HookLoaderPlugin_Base $Plugin)
	{
		$this->Plugin = $Plugin;
	}
}