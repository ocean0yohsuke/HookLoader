<?php

class phpBB3_HookLoaderAdmMain_HLP_PanelPage_pluginInfo extends phpBB3_HookLoaderAdmMain_HLP_PanelPage_base
{
	function main()
	{
		global $template;
		
		$template->assign_vars(array(
			'S_TEMPLATETYPE'	=> 'pluginInfo',
			'TITLE'			=> $this->lang['PlUGININFO_TITLE'],
		));
		
		$plugin_info = $this->PluginAdm->Info->plugin();
		
		$template->assign_vars(array(
			'PLUGININFO_PLUGIN_TITLE' 				=> $this->PluginAdm->plugin_name,
			'PLUGININFO_PLUGIN_DESCRIPTION' 		=> (isset($plugin_info['description']))? $plugin_info['description'] : '---',
			'PLUGININFO_PLUGIN_VERSION'				=> (isset($plugin_info['version']))? $plugin_info['version'] : '---',
			'PLUGININFO_PLUGIN_PHPVERSION'			=> (isset($plugin_info['PHP-version']))? $plugin_info['PHP-version'] : '---',
			'PLUGININFO_PLUGIN_PHPEXTENSIONS'		=> (isset($plugin_info['PHP-extensions']))? $this->intersperse(', ', $plugin_info['PHP-extensions']) : '---',
			'PLUGININFO_PLUGIN_PHPBBVERSION'		=> (isset($plugin_info['phpBB-version']))? $plugin_info['phpBB-version'] : '---',
			'PLUGININFO_PLUGIN_HOOKLOADERVERSION'	=> (isset($plugin_info['HookLoader-version']))? $plugin_info['HookLoader-version'] : '---',
			'PLUGININFO_PLUGIN_AUTHORNOTES'			=> (isset($plugin_info['author-notes']))? $plugin_info['author-notes'] : '---',
			'PLUGININFO_AUTHOR_NAME'					=> (isset($plugin_info['author']['name']))? $plugin_info['author']['name'] : '---',
			'PLUGININFO_AUTHOR_WWW'					=> (isset($plugin_info['author']['www']))? $plugin_info['author']['www'] : '---',
		));
	}
	
	private function intersperse($delimiter, $array)
	{
		$result = '';
		foreach ($array as $key => $value)
		{
			$result .= $value . $delimiter;
		}
		$result = preg_replace('/' . $delimiter . '$/', '', $result);
		return $result;
	}
}
