<?php

class phpBB3_HookLoaderAdmMain_HLP_PanelPage_setup extends phpBB3_HookLoaderAdmMain_HLP_PanelPage_base
{
	function main()
	{
		global $template;
		
		$error = array();
		$this->php_version_check($error);
		$this->phpbb_version_check($error);
		$this->hookloader_version_check($error);
		$this->php_extensions_check($error);
		if (sizeof($error)) {
			$template->assign_vars(array(
				'S_ERROR'			=> TRUE,
				'ERROR_MSG'		=> implode('<br />', $error),
			));
			return;
		}
		
		$mode = request_var('mode', 'intro');

		$template->assign_vars(array(
			'S_TEMPLATETYPE'	=> 'setup',
			'S_SETUPMODE'		=> $mode,
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

	private function php_version_check(&$error)
	{
		$plugin_version = $this->PluginAdm->Info->plugin('PHP-version');
		if (isset($plugin_version)) {
			if (version_compare(PHP_VERSION, $plugin_version, '<')) {
				$error[] = sprintf($this->lang['PHPVERSION_IS_TOO_LOW'], $plugin_version);
			}
		}
	}
	private function phpbb_version_check(&$error)
	{
		$plugin_version = $this->PluginAdm->Info->plugin('phpBB-version');
		if (isset($plugin_version)) {
			global $config;
			$version = $config['version'];
			if (version_compare($version, $plugin_version, '<')) {
				$error[] = sprintf(sprintf($this->lang['PHPBBVERSION_IS_TOO_LOW'], $plugin_version));
			}
		}
	}
	private function hookloader_version_check(&$error)
	{
		if ($this->PluginAdm->plugin_name === 'HookLoader') {
			return TRUE;
		}
		$plugin_version = $this->PluginAdm->Info->plugin('HookLoader-version');
		if (isset($plugin_version)) {
			$HookLoaderPlugin = new phpBB3_HookLoaderPlugin('HookLoader');
			$HookLoaderPluginAdm = $HookLoaderPlugin->Adm();
			$hookloader_version = $HookLoaderPluginAdm->Info->plugin('HookLoader-version');
			if (version_compare($hookloader_version, $plugin_version, '<')) {
				$error[] = sprintf(sprintf($this->lang['HOOKLOADERVERSION_IS_TOO_LOW'], $plugin_version));
			}
		}
	}
	private function php_extensions_check(&$error)
	{
		$php_extensions = $this->PluginAdm->Info->plugin('PHP-extensions');
		if (isset($php_extensions)) {
			foreach ($php_extensions as $extension) {
				if (!extension_loaded($extension))	{
					$error[] = sprintf(sprintf($this->lang['PHPBEXTENSION_IS_NOT_FOUND'], $extension));
				}
			}
		}
	}

	private function intro()
	{
		global $template;
		
		add_form_key('setup_run');
		
		$template->assign_vars(array(
			'TITLE'				=> $this->lang['SETUPPANEL_SETUP_TITLE'],
			'TITLE_EXPLAIN'		=> sprintf($this->lang['SETUPPANEL_SETUP_MESSAGE'], $this->PluginAdm->plugin_name, $this->PluginAdm->Info->plugin('version')),
		
			'U_ACTION'		=> append_sid('index.php', "tabmenu={$this->PluginAdm->plugin_name}&amp;sidemenu=setup&amp;mode=run"),
		));
	}

	private function run()
	{
		global $template;
		
		if ($this->PluginAdm->isSetup()) {
			throw new phpBB3_HookLoaderException("This plugin does not need to be setup.");
		}
		if (!check_form_key('setup_run')) {
			throw new phpBB3_HookLoaderException($this->lang['FORM_INVALID']);
		}
		
		$PluginSetup = $this->PluginAdm->Setup();
		$PluginSetup->setup();

		$message = sprintf($this->lang['SETUPPANEL_SETUP_SUCCESSED'], $this->PluginAdm->plugin_name, $this->PluginAdm->config['version']);
		$message .= '<br /><br />';
		$message .= implode('<br />', $PluginSetup->messages());
		
		$template->assign_vars(array(
			'MESSAGE'		=> $message,
			'U_GOTO_MENU'	=> append_sid('index.php', "tabmenu={$this->PluginAdm->plugin_name}"),
		));
	}
}