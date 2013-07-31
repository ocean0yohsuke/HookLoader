<?php

class phpBB3_HookLoaderPluginAdm_BanCountry_Config
{
	public $API;
	
	function main()
	{
		$Panel = $this->API->Template()->Setting();
		$Panel->create_head($this->API->Plugin->lang['CONFIG_TITLE'], $this->API->Plugin->lang['CONFIG_TITLE_EXPLAIN']);
		/**
		 *	Validation types are:
		 *		string, int, bool,
		 *		script_path (absolute path in url - beginning with / and no trailing slash),
		 *		rpath (relative), rwpath (realtive, writable), path (relative path, but able to escape the root), wpath (writable)
		 */
		$Panel->set_display_vars(array(
			'legend1'		=> 'GENERAL_SETTINGS',
				'enabled'	=> array('lang' => 'ENABLE_BANCOUNTRY',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => TRUE),
			'legend2'		=> 'BAN_SETTINGS',
				'checkRegistrationIP'	
							=> array('lang' => 'CHECK_REGISTRATION_IP',	'validate' => 'bool',	'type' => 'radio:yes_no', 	'explain' => TRUE),				
		));
		$Panel->create_body();
	}
}
