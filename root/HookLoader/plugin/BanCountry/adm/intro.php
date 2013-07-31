<?php

class phpBB3_HookLoaderPluginAdm_BanCountry_intro
{
	function main()
	{
		$Panel = $this->API->Template()->Custom();
		$Panel->set_template('intro.html');
		$Panel->create_head($this->API->Plugin->lang['INTRO_TITLE']);
		$Panel->create_body();
	}
}