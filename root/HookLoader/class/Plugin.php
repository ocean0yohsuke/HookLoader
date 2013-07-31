<?php

class phpBB3_HookLoaderPlugin
{
	private $name;
	private $config;
	private $Info;

	private $Adm;
	private $Hook;
	
	function __construct($name)
	{
		$this->name = $name;
		$this->set_config();
		$this->setInfo();
	}

	private function set_config()
	{
		global $config;

		$this->config = array();
		$prefix = 'HookLoader_' . $this->name . '_';
		foreach($config as $key => $value)
		{
			if (preg_match("/^{$prefix}/i", $key)) {
				$config_name = preg_replace("/^{$prefix}/i", '', $key);
				$this->config[$config_name] = $value;
			}
		}
	}
	private function setInfo()
	{
		$this->Info = new phpBB3_HookLoaderPluginInfo($this->name);
	}
	
	private function setAdm()
	{
		$Adm = phpBB3_HookLoaderUtil::ObjectFileSystem('class/Adm/Plugin', 'AdmPlugin', array(
			'plugin_name' => $this->name,
			'config' 		=> $this->config,
			'Info' 		=> $this->Info,
		));
		$this->Adm = $Adm->Main();
	}
	private function setHook()
	{
		$Hook = phpBB3_HookLoaderUtil::ObjectFileSystem('class/Hook/Plugin', 'HookPlugin', array(
			'plugin_name' => $this->name,
			'config' 		=> $this->config,
			'Info' 		=> $this->Info,
		));
		$this->Hook = $Hook->Main();
	}
	function Adm()
	{
		if (!isset($this->Adm)) {
			$this->setAdm();
		}
		return $this->Adm;
	}
	function Hook()
	{
		if (!isset($this->Hook)) {
			$this->setHook();
		}
		return $this->Hook;
	}
}

class phpBB3_HookLoaderPlugin_Base extends ObjectFileSystemFile
{
	public $plugin_name;
	public $Info;
	public $config;

	function set_config($config_name, $config_value, $is_dynamic = FALSE)
	{
		global $config, $db;

		$prefix = 'HookLoader_' . $this->plugin_name . '_';
		if ($config_value === NULL)
		{
			unset($this->config[$config_name]);

			$sql = 'DELETE FROM ' . CONFIG_TABLE . " WHERE config_name = '" . $db->sql_escape($prefix . $config_name) . "'";
			$db->sql_query($sql);
		}
		else
		{
			$this->config[$config_name] = $config_value;

			set_config($prefix . $config_name, $config_value, $is_dynamic);
		}
	}
	
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
	
	private function set_plugin()
	{
		if (!is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/info.php")) {
			return;
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
	private function set_adm()
	{
		if (!is_file(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/info.php")) {
			return;
		}
		require PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/info.php";
		$this->adm = $adm;
	}
	private function set_config_default()
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
			$this->set_plugin();
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
			$this->set_adm();
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
			$this->set_config_default();
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