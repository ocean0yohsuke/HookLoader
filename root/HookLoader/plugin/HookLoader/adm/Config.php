<?php

class phpBB3_HookLoaderPluginAdm_HookLoader_Config
{
	public $API;
	
	function main()
	{	
		$Panel = $this->API->Template()->Setting();
		$Panel->create_head($this->API->Plugin->lang['CONFIG'], $this->API->Plugin->lang['CONFIG_EXPLAIN']);
		
		/**
		 *	Validation types are:
		 *		string, int, bool,
		 *		script_path (absolute path in url - beginning with / and no trailing slash),
		 *		rpath (relative), rwpath (realtive, writable), path (relative path, but able to escape the root), wpath (writable)
		 */
		$Panel->set_display_vars(array(
			'legend1' => 'GENERAL_SETTINGS',
				'enabled' => array('lang' => 'HOOKLOADER_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
			'legend2' => 'HOOK_SETTINGS',
				'phpbb_user_session_handler_enabled' => array('lang' => 'PHPBB_USER_SESSION_HANDLER_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'template_display_enabled' => array('lang' => 'TEMPLATEDISPLAY_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'append_sid_enabled' => array('lang' => 'APPENDSID_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'exit_handler_enabled' => array('lang' => 'EXITHANDLER_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
		));
		$Panel->create_body();
	}
}