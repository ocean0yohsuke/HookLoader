<?php

class phpBB3_HookLoaderPlugin_Base extends ObjectFileSystemFile
{
	public $plugin_name;
	public $lang;
	public $config;
	public $Info;
	
	function isSetup()
	{
		if (!isset($this->config['version'])) {
			return FALSE;
		}
		$version_config = $this->config['version'];
		$version_info	= $this->Info->plugin('version');
		if (!isset($version_info)) {
			return FALSE;
		}
		return ($version_info === $version_config);
	}
	
	function isEnabled()
	{
		if (!isset($this->config['version'])) {
			return FALSE;
		}
		$version = $this->config['version'];
		if (!isset($this->config['enabled'])) {
			return TRUE;
		}
		$enabled = $this->config['enabled'];
		return ($enabled) ? TRUE : FALSE;
	}
}