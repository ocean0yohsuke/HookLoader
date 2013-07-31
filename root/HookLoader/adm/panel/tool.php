<?php

class phpBB3_HookLoaderAdmMain_HLP_PanelPage_tool extends phpBB3_HookLoaderAdmMain_HLP_PanelPage_base
{
	function main($adm_name)
	{
		$plugin_name = $this->PluginAdm->plugin_name;
		
		$HookLoaderAPI = new phpBB3_HookLoaderAPI($plugin_name);
		$APIAdm = $HookLoaderAPI->Adm($adm_name);

		//
		// base
		//
		$path = PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$plugin_name}/adm/base.php";
		$class_name = "phpBB3_HookLoaderPluginAdm_{$plugin_name}_base";
		if (is_file($path))	{
			include_once $path;
			if (defined('DEBUG')) {
				if (!class_exists($class_name, FALSE))	{
					throw new phpBB3_HookLoaderException("Could not find class '{$class_name}' in '{$path}'.");
				}
			}
		}
			
		//
		// tool
		//
		$path = PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$plugin_name}/adm/{$adm_name}.php";
		$class_name = "phpBB3_HookLoaderPluginAdm_{$plugin_name}_{$adm_name}";
		if (defined('DEBUG') && !is_file($path))	{
			throw new phpBB3_HookLoaderException("Could not find file '{$path}'.");
		}
		include_once $path;
		if (defined('DEBUG')) {
			if (!class_exists($class_name, FALSE))	{
				throw new phpBB3_HookLoaderException("Could not find class '{$class_name}' in '{$path}'.");
			}
			if (!method_exists($class_name, 'main')) {
				throw new phpBB3_HookLoaderException("Cound not find method 'main' within the class '{$class_name}' in '{$path}'.");
			}
		}
		$PluginTool = new $class_name;
		$PluginTool->API = $APIAdm;
		$PluginTool->main();
	}
}