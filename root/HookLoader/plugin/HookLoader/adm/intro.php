<?php

class phpBB3_HookLoaderPluginAdm_HookLoader_intro
{
	public $API;
	
	function main()
	{
		$Panel = $this->API->Template()->Custom();
		$Panel->set_template('intro.html');
		$Panel->create_head($this->API->Plugin->lang['HOOKLOADER_INTRO_TITLE']);
		$Panel->create_body();
	}
}
