<?php

class phpBB3_HookLoaderAPI
{
	private $Plugin;
	
	function __construct($plugin_name)
	{
		$this->Plugin = new phpBB3_HookLoaderPlugin($plugin_name);
		
		return $this;
	}
	
	function Hook($hook_name)
	{
		$Hook = phpBB3_HookLoaderUtil::ObjectFileSystem('class/Hook/API', 'HookAPI', array(
			'Plugin' 		=> $this->Plugin->Hook(),
			'hook_name'	=> $hook_name,
		));
		return $Hook;
	}
	function Adm($adm_name)
	{
		$Adm = phpBB3_HookLoaderUtil::ObjectFileSystem('class/Adm/API', 'AdmAPI', array(
			'Plugin' 		=> $this->Plugin->Adm(),
			'adm_name'		=> $adm_name,
		));
		return $Adm;
	}
}