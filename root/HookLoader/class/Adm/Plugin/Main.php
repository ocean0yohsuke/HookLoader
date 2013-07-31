<?php

class phpBB3_HookLoaderAdmPlugin_Main extends phpBB3_HookLoaderPlugin_Base
{
	public $lang;
	
	function __construct($File)
	{
		parent::__construct($File);
		
		$this->load_lang();
	}
	
	function main()
	{
		global $template;
		$template->assign_vars(array(
			'HOOKLOADER_PLUGIN_TITLE' 		=> $this->plugin_name,
			'HOOKLOADER_PLUGIN_VERSION' 	=> $this->Info->plugin('version'),
		));
	}
	
	private function load_lang()
	{
		global $user;

		$lang = array();
		if (is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/adm/{$user->lang_name}.php"))
		{
			include PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/adm/{$user->lang_name}.php";
		}
		elseif (is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/adm/en.php}"))
		{
			include PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/adm/en.php}";
		}

		$user->lang += $lang;
		$this->lang = $lang;
	}
	
	function haveIntro()
	{
		if (is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/adm/intro.php")) {
			return TRUE;
		}
		return FALSE;
	}
}