<?php

class phpBB3_HookLoaderPlugin_Adm_Setup extends phpBB3_HookLoaderPlugin_AdmBase
{
	private $prefix;
	private $messages = array();

	function construct()
	{
		$version = $this->Info->plugin('version');
		if (!isset($version)) {
			throw new phpBB3_HookLoaderException("The contents of info.php of plugin '{$this->plugin_name}' is invalid: The version is null.");
		}
		
		$this->prefix = 'HookLoader_' . $this->plugin_name . '_';
	}

	function main()
	{
	}
	
	function setup()
	{
		$this->validate_config();
		phpBB3_HookLoaderAdapter::purge_cache();
	}

	function unsetup()
	{
		$this->delete_config();
		phpBB3_HookLoaderAdapter::purge_cache();
	}

	function messages()
	{
		return $this->messages;
	}

	private function addMessage($message)
	{
		$this->messages[] = $message;
	}

	private function validate_config()
	{
		global $config, $db, $user;

		$default_config['version'] = $this->Info->plugin('version');
		$ary = $this->Info->config_default();
		if (isset($ary)) {
			$default_config += $ary;
		}

		// garbage collection
		foreach($this->config as $config_name => $config_var)
		{
			if (isset($default_config[$config_name]))	{
				continue;
			}
			$this->set_config($config_name, NULL);
			$this->addMessage(sprintf($user->lang['SETUPPANEL_CONFIGKEY_DELETED'], $config_name));
		}
		
		//
		// set config
		//
		if (isset($this->config['version'])) {
			$message = sprintf($user->lang['SETUPPANEL_CONFIGKEY_UPDATED'], $this->prefix . 'version', $this->Info->plugin('version'));
		} else {
			$message = sprintf($user->lang['SETUPPANEL_CONFIGKEY_ADDED'], $this->prefix . 'version');
		}
		$this->set_config('version', $default_config['version']);
		$this->addMessage($message);
		foreach ($default_config as $config_name => $config_var)
		{
			if (isset($this->config[$config_name])) {
				continue;
			}
			$this->set_config($config_name, $config_var);
			$this->addMessage(sprintf($user->lang['SETUPPANEL_CONFIGKEY_ADDED'], $this->prefix . $config_name));
		}
	}
	
	private function delete_config()
	{
		global $db, $config, $user;

		if (isset($this->config)) {
			foreach($this->config as $config_name => $config_var)
			{
				$this->set_config($config_name, NULL);
				$this->addMessage(sprintf($user->lang['SETUPPANEL_CONFIGKEY_DELETED'], $this->prefix . $config_name));
			}
		}
	}
}