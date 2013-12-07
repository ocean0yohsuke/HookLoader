<?php

class phpBB3_HookLoaderMain
{
	private $HookLoaderPlugin;
	private $OFS;

	function __construct()
	{
		if (!class_exists('ObjectFileSystem')) {
			include_once(PHPBB_HOOKLOADER_ROOT_PATH . 'include/FileSystemOOP/ObjectFileSystem.php');
		}
		if (!class_exists('MethodFileSystem')) {
			include_once(PHPBB_HOOKLOADER_ROOT_PATH . 'include/FileSystemOOP/MethodFileSystem.php');
		}
		
		$this->HookLoaderPlugin = new phpBB3_HookLoaderPlugin('HookLoader');
		$this->OFS = phpBB3_HookLoaderUtil::ObjectFileSystem('class/Main', 'Main');
	}

	/**
	* ACP Index
	*/
	function acpi()
	{
		return $this->OFS->ACPI();
	}
	
	function Hook()
	{
		return $this->OFS->Hook();
	}
	
	function Adm_run()
	{
		$this->OFS->Adm()->run();
	}
	
	
	function hookIsEnabled($hook_type)
	{
		if (isset($this->HookLoaderPlugin->Hook()->config[$hook_type . '_enabled'])) {
			$enabled = $this->HookLoaderPlugin->Hook()->config[$hook_type . '_enabled'];
		}
		return (isset($enabled) && $enabled) ? TRUE : FALSE;
	}
	function isEnabled($hook_type = NULL)
	{
		return $this->HookLoaderPlugin->isEnabled();	
	}
	function isSetup()
	{
		return $this->HookLoaderPlugin->isSetup();
	}
}
