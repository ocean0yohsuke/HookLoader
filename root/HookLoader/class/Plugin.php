<?php

class phpBB3_HookLoaderPlugin
{
	private $plugin_name;
	private $lang;
	private $config;
	private $Info;
	
	private $OFS;
	private $Adm;
	private $Hook;
	
	function __construct($plugin_name)
	{
		$this->plugin_name = $plugin_name;
		$this->load_lang();
		$this->make_config();
		$this->Info = new phpBB3_HookLoaderPluginInfo($plugin_name);
		
		$this->OFS = phpBB3_HookLoaderUtil::ObjectFileSystem('class/Plugin', 'Plugin', array(
			'plugin_name'	=> $this->plugin_name,
			'lang'		=> $this->lang,
			'config'	=> $this->config,
			'Info' 	=> $this->Info,
		));
	}
	private function load_lang() {
		global $user;
		$lang = array ();
		if (defined ( 'ADMIN_START' )) {
			if (is_file ( PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/adm/{$user->lang_name}.php" )) {
				include PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/adm/{$user->lang_name}.php";
			} elseif (is_file ( PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/adm/en.php}" )) {
				include PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/adm/en.php}";
			}
		} else {
			if (is_file ( PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/{$user->lang_name}.php" )) {
				include PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/{$user->lang_name}.php";
			} elseif (is_file ( PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/en.php}" )) {
				include PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/language/en.php}";
			}
		}
		$user->lang += $lang;
		
		$this->lang = $lang;
	}
	private function make_config()
	{
		global $config;

		$this->config = array();
		$prefix = 'HookLoader_' . $this->plugin_name . '_';
		foreach($config as $key => $value) 
		{
			if (preg_match("/^{$prefix}/i", $key)) {
				$config_name = preg_replace("/^{$prefix}/i", '', $key);
				$this->config[$config_name] = $value;
			}
		}
	}
	
	function Adm()
	{
		return $this->OFS->Adm();
	}
	function Hook()
	{
		return $this->OFS->Hook();
	}
	
	function isSetup()
	{
		return $this->Adm()->isSetup();
	}
	function isEnabled()
	{
		return $this->Adm()->isEnabled();
	}
}

class phpBB3_HookLoaderPluginInfo
{
	private $plugin_name;

	private $plugin;
	private $adm;
	private $config_default;

	function __construct($plugin_name)
	{
		$this->plugin_name = $plugin_name;
	}
	
	private function make_plugin()
	{
		if (!is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/info.php")) {
			throw new phpBB3_HookLoaderException("The file of information (info.php) for plugin '{$this->plugin_name}' is missing.");
		}
		require PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/info.php";

		if (isset($plugin['version']) && !preg_match('/^(?:0|[^0]\d*)(?:\.(?:0|[^0]\d*)){0,3}$/', $plugin['version'])) {
			throw new phpBB3_HookLoaderException("The contents of info.php of plugin '{$this->plugin_name}' is wrong: version is invalid.");
		}
		if (isset($plugin['PHP-version']) && !preg_match('/^(?:0|[^0]\d*)(?:\.(?:0|[^0]\d*)){0,3}$/', $plugin['PHP-version'])) {
			throw new phpBB3_HookLoaderException("The contents of info.php of plugin '{$this->plugin_name}' is wrong: PHP-version is invalid.");
		}
		if (isset($plugin['phpBB-version']) && !preg_match('/^(?:0|[^0]\d*)(?:\.(?:0|[^0]\d*)){0,3}$/', $plugin['phpBB-version'])) {
			throw new phpBB3_HookLoaderException("The contents of info.php of plugin '{$this->plugin_name}' is wrong: phpBB-version is invalid.");
		}
		if (isset($plugin['HookLoader-version']) && !preg_match('/^(?:0|[^0]\d*)(?:\.(?:0|[^0]\d*)){0,3}$/', $plugin['HookLoader-version'])) {
			throw new phpBB3_HookLoaderException("The contents of info.php of plugin '{$this->plugin_name}' is wrong: HookLoader-version is invalid.");
		}
		if (isset($plugin['PHP-exetnsions']) && !is_array($plugin['PHP-extensions'])) {
			throw new phpBB3_HookLoaderException("The contents of info.php of plugin '{$this->plugin_name}' is wrong: PHP-extensions is invalid.");
		}
		
		$this->plugin = $plugin;
	}
	private function make_adm()
	{
		if (!is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/info.php")) {
			return;
		}
		require PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/info.php";
		$this->adm = $adm;
	}
	private function make_config_default()
	{
		if (!is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/info.php")) {
			return;
		}
		require PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/info.php";
		$this->config_default = $config_default;
	}

	function plugin($key = NULL)
	{
		if (!isset($this->plugin)) {
			$this->make_plugin();
		}

		if (!isset($key)) {
			return $this->plugin;
		} elseif (!isset($this->plugin[$key])) {
			return NULL;
		} else	{
			return $this->plugin[$key];
		}
	}

	function adm($key = NULL)
	{
		if (!isset($this->adm)) {
			$this->make_adm();
		}

		if (!isset($key))	{
			return $this->adm;
		} elseif (!isset($this->adm[$key])) {
			return NULL;
		} else	{
			return $this->adm[$key];
		}
	}

	function config_default($key = NULL)
	{
		if (!isset($this->config_default)) {
			$this->make_config_default();
		}

		if (!isset($key))	{
			return $this->config_default;
		} elseif (!isset($this->config_default[$key])) {
			return NULL;
		} else	{
			return $this->config_default[$key];
		}
	}
}