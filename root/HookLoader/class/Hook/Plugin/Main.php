<?php

class phpBB3_HookLoaderHookPlugin_Main extends phpBB3_HookLoaderPlugin_Base
{
	public $lang;
	
	function __construct($File)
	{
		parent::__construct($File);
		
		$this->load_lang();
	}
	
	function main()
	{
	}
	
	private function load_lang()
	{
		global $user;

		$lang = array();
		if (is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/{$user->lang_name}.php"))
		{
			include PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/{$user->lang_name}.php";
		}
		elseif (is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/en.php}"))
		{
			include PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/en.php}";
		}

		$user->lang += $lang;
		$this->lang = $lang;
	}	
}