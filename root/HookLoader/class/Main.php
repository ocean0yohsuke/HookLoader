<?php

class phpBB3_HookLoaderMain
{
	private $HookLoaderPluginAdm;

	function __construct()
	{
		if (!class_exists('ObjectFileSystem')) {
			include_once(PHPBB_HOOKLOADER_ROOT_PATH . 'include/FileSystemOOP/ObjectFileSystem.php');
		}
		if (!class_exists('MethodFileSystem')) {
			include_once(PHPBB_HOOKLOADER_ROOT_PATH . 'include/FileSystemOOP/MethodFileSystem.php');
		}
		
		$HookLoaderPlugin = new phpBB3_HookLoaderPlugin('HookLoader');
		$this->HookLoaderPluginAdm = $HookLoaderPlugin->Adm();
		
		return $this;
	}

	function Hook()
	{
		$Hook = phpBB3_HookLoaderUtil::ObjectFileSystem('class/Hook/Main', 'HookMain');
		return $Hook;
	}
	function Adm()
	{
		$Adm = phpBB3_HookLoaderUtil::ObjectFileSystem('class/Adm/Main', 'AdmMain');
		return $Adm;
	}

	function isEnabled($hook_type = NULL)
	{
		if (!isset($hook_type)) {
			return $this->HookLoaderPluginAdm->isEnabled();
		}
		if (isset($this->HookLoaderPluginAdm->config[$hook_type . '_enabled'])) {
			$enabled = $this->HookLoaderPluginAdm->config[$hook_type . '_enabled'];
		}
		return (isset($enabled) && $enabled) ? TRUE : FALSE;
	}
	function isSetup()
	{
		$this->HookLoaderPluginAdm->isSetup();
	}
}