<?php
include_once PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . 'BanCountry/autoload.php';

class phpBB3_HookLoaderPluginHook_BanCountry_BanCountry
{
	public $API;
	
	function phpbb_user_session_handler()
	{
		if (!$this->API->Plugin->isEnabled()) {
			return;
		}
		
		try {
			$BanCountry = new phpBB3_BanCountryMain($this->API->Plugin);
			$BanCountry->filter();
		}
		catch (phpBB3_BanCountryException $e) {
			$e->getException();
		}
	}
}