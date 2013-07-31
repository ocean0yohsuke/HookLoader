<?php

class phpBB3_HookLoaderAdmAPI_Page extends phpBB3_HookLoaderAdmAPI_Base
{
	function main()
	{
	}
	
	function root_url()
	{
		$url = PHPBB_HOOKLOADER_ROOT_PATH . 'adm/index.php?tabmenu=' . $this->Plugin->plugin_name  . '&amp;sidemenu=' . $this->adm_name;
		return $url; 
	}
}