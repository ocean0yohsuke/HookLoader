<?php

class phpBB3_HookLoaderAdmMain_HLP_PanelPage_unsetup extends phpBB3_HookLoaderAdmMain_HLP_PanelPage_base
{
	function main()
	{
		$mode = request_var('mode', 'intro');
		
		global $template;

		$template->assign_vars(array(
			'S_TEMPLATETYPE'	=> 'unsetup',
			'S_UNSETUPMODE'	=> $mode,
		));
			
		switch ($mode)
		{
			case 'intro' :
				$this->intro();
				break;
			case 'run' :
				$this->run();
				break;
			default :
				throw new phpBB3_HookLoaderException("Invalid mode was specified: '$mode'");
		}
	}

	function intro()
	{
		global $template;
		
		add_form_key('unsetup_run');

		$template->assign_vars(array(
			'TITLE'		=> $this->lang['SETUPPANEL_UNSETUP_TITLE'],
			'TITLE_EXPLAIN'	=> sprintf($this->lang['SETUPPANEL_UNSETUP_MESSAGE'], $this->PluginAdm->plugin_name, $this->PluginAdm->Info->plugin('version')),
			'U_ACTION'	=> append_sid('index.php', "tabmenu={$this->PluginAdm->plugin_name}&amp;sidemenu=unsetup&amp;mode=run"),
		));
	}

	function run()
	{
		global $template, $config;
		
		if (!check_form_key('unsetup_run'))
		{
			throw new phpBB3_HookLoaderException($this->lang['FORM_INVALID']);
		}

		$message = sprintf($this->lang['SETUPPANEL_UNSETUP_SUCCESSED'], $this->PluginAdm->plugin_name, $this->PluginAdm->config['version']);
		
		$PluginSetup = $this->PluginAdm->Setup();
		$PluginSetup->unsetup();

		$message .= '<br /><br />';
		$message .= implode('<br />', $PluginSetup->messages());

		$template->assign_vars(array(
			'MESSAGE'	=> $message,
			'U_GOTO_MENU'	=> append_sid('index.php', "tabmenu={$this->PluginAdm->plugin_name}"),
		));
	}
}