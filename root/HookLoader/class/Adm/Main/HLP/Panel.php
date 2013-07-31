<?php

class phpBB3_HookLoaderAdmMain_HLP_Panel extends ObjectFileSystemFile
{
	public $lang;
	
	private $Info;
	private $Var;
	private $PluginAdm;

	function __construct($File)
	{
		parent::__construct($File);
		
		$this->Info 	= new phpBB3_HookLoaderAdm_Main_PanelInfo();
		$this->Var 	= new phpBB3_HookLoaderAdm_Main_PanelVar();
	}
	
	function main()
	{
		$this->Var->set_request_var('tabmenu', 'HookLoader');
		$plugin_name 			= $this->Var->get_request_var('tabmenu');
		$Plugin = new phpBB3_HookLoaderPlugin($plugin_name);
		$this->PluginAdm 		= $Plugin->Adm();
		$haveIntro 			= $this->PluginAdm->haveIntro();
		$default_sidemenu 	= ($haveIntro)? 'intro' : 'pluginInfo';
		$this->Var->set_request_var('sidemenu', $default_sidemenu);

		$PageMFS = phpBB3_HookLoaderUtil::MethodFileSystem('adm/panel', 'AdmMain_HLP_PanelPage', array(
			'PluginAdm' 	=> $this->PluginAdm,
			'lang'			=> $this->lang,
		));
		$Page = $PageMFS->__invoke('.');
		
		$this->create_tabmenu();
		
		try {
			$sidemenu = $this->Var->get_request_var('sidemenu');
			switch ($sidemenu)
			{
				case 'intro' :
					$this->create_sidemenu();
					if ($haveIntro) {
						$Page->intro();
					} else {
						$Page->error($this->lang['SIDEMENU_ERROR_INVALID']);
					}
					break;
				case 'pluginInfo' :
					$this->create_sidemenu();
					$Page->pluginInfo();
					break;
				case '' :
					$Page->error($this->lang['SIDEMENU_ERROR_EMPTY']);
					break;
				default :
					if ($this->PluginAdm->isSetup() && $sidemenu === 'unsetup') {
						$Page->unsetup();
						$this->create_sidemenu();
					} elseif (!$this->PluginAdm->isSetup() && $sidemenu === 'setup') {
						$Page->setup();
						$this->create_sidemenu();
					} else	{
						if ($this->PluginAdm->isSetup()) {
							$this->create_sidemenu();
							$Page->tool($sidemenu);
						} else {
							$Page->error($this->lang['SIDEMENU_ERROR_INVALID']);
						}
					}
			}
		}
		catch (MethodFileSystemException $e) {
			if (defined('DEBUG')) {
				$e->getException();
			} else {
				throw new phpBB3_HookLoaderException('[MethodFileSystem Error] ' . $e->getMessage());
			}
		}

		$this->create_errorblock($plugin_name);
	}

	private function create_errorblock($plugin_name)
	{
		global $template;

		if ($plugin_name === 'HookLoader')	{
			return;
		}

		$HookLoaderPlugin = new phpBB3_HookLoaderPlugin('HookLoader');
		$HookLoaderPluginAdm = $HookLoaderPlugin->Adm();
		if (!$HookLoaderPluginAdm->isSetup())	{
			$template->assign_vars(array(
				'S_NOTICE'	=> true,
				'NOTICE_MSG'	=> $this->lang['HOOKLOADER_ISNOT_SETUP']
			));
			return;
		} elseif (!$HookLoaderPluginAdm->isEnabled()) {
			$template->assign_vars(array(
				'S_NOTICE'		=> true,
				'NOTICE_MSG'	=> $this->lang['HOOKLOADER_ISNOT_ENABLED']
			));
			return;
		}
	}

	private function create_tabmenu()
	{
		$tabmenu = $this->load_tabmenu();
		$tabmenu = $this->sort_menu_by_order($tabmenu, $this->Info->adm('menu_order'));
		foreach ($tabmenu as $tab_name)
		{
			$this->create_tabular_block($tab_name);
		}
	}

	private function load_tabmenu()
	{
		$tabs = array();
		foreach( new DirectoryIterator(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH) as $iterator )
		{
			if ($iterator->isDir() && !$iterator->isDot()) {
				$plugin_name = $iterator->getFilename();
				if (file_exists(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$plugin_name}/adm/")) {
					$tabs[] = $plugin_name;
				}
			}
		}
		return $tabs;
	}

	private function create_sidemenu()
	{
		$isSetup 	= $this->PluginAdm->isSetup();
		$haveIntro 	= $this->PluginAdm->haveIntro();
		$plugin_version = $this->PluginAdm->Info->plugin('version');

		if (!$isSetup) {
			if ($haveIntro) {
				$this->create_linear_block('intro');
			}
			$this->create_linear_block('pluginInfo');
			if (isset($plugin_version)) {
				$this->create_linear_block('setup');
			}
		} else	{
			$plugin_name 	= $this->PluginAdm->plugin_name;

			$sidemenu_list = array();
			foreach( new DirectoryIterator(PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$plugin_name}/adm/") as $iterator )
			{
				if ($iterator->isFile()) {
					$file_name = $iterator->getFilename();
					if (!in_array($file_name, array(
						'base.php',
						'intro.php',
						'pluginInfo.php',
						'setup.php',
						'unsetup.php')))
					{
						$sidemenu_list[] = basename($file_name, '.php');
					}
				}
			}
			$sidemenu_list = $this->sort_menu_by_order($sidemenu_list, $this->PluginAdm->Info->adm('menu_order'));

			foreach($sidemenu_list as $menu_name)
			{
				$this->create_linear_block(basename($menu_name, '.php'));
			}

			if ($haveIntro) {
				$this->create_linear_block('intro');
			}
			$this->create_linear_block('pluginInfo');
			if (isset($plugin_version)) {
				$this->create_linear_block('unsetup');
			}
		}
	}

	private function create_tabular_block($block)
	{
		global $template;

		$template->assign_block_vars('t_block1', array(
			'L_TITLE'		=> $block,
			'S_SELECTED'	=> ($block == $this->Var->get_request_var('tabmenu'))? true : false,
			'U_TITLE'		=> append_sid('index.php', 'tabmenu=' . $block),
		));
	}

	private function create_linear_block($block, $block_type = '1')
	{
		global $template;
			
		if (!in_array($block, array('intro', 'pluginInfo', 'setup', 'unsetup'))) {
			$title_lang_key = strtoupper($this->Var->get_request_var('tabmenu')) . '_SIDEMENU_' . strtoupper($block);
		} else {
			$title_lang_key = 'SIDEMENU_' . strtoupper($block);
		}

		$template->assign_block_vars('l_block' . $block_type, array(
			'L_TITLE'		=> (isset($this->lang[$title_lang_key]))? $this->lang[$title_lang_key] : $block,
			'S_SELECTED'	=> ($block == $this->Var->get_request_var('sidemenu'))? true : false,
			'U_TITLE'		=> append_sid('index.php', "tabmenu={$this->Var->get_request_var('tabmenu')}&amp;sidemenu=$block"),
		));
	}

	private function sort_menu_by_order($menu_list, $menu_order_list)
	{
		if (!isset($menu_order_list)) {
			return $menu_list;
		}

		$max_order = count($menu_order_list);
		foreach ($menu_order_list as $menu_name => $order)
		{
			if ($order > $max_order) {
				$max_order = $order;
			}
		}

		$new_menu_list = array();
		foreach ($menu_list as $i => $menu_name)
		{
			if (isset($menu_order_list[$menu_name])) {
				$new_menu_list[$menu_name] = $menu_order_list[$menu_name];
			} else {
				$new_menu_list[$menu_name] = $max_order + ($i+1);
			}
		}
		$menu_list = array_flip($new_menu_list);
		ksort($menu_list);

		return $menu_list;
	}
}

class phpBB3_HookLoaderAdm_Main_PanelInfo
{
	private $adm;

	function __construct()
	{
		if (is_file(PHPBB_HOOKLOADER_ROOT_PATH . "adm/info.php")) {
			require PHPBB_HOOKLOADER_ROOT_PATH . "adm/info.php";
			if (isset($adm)) {
				$this->adm = $adm;
			}
		}
	}

	function adm($key = null)
	{
		if (!isset($key)) {
			return $this->adm;
		} elseif (!isset($this->adm[$key])) {
			return null;
		} else	{
			return $this->adm[$key];
		}
	}
}

class phpBB3_HookLoaderAdm_Main_PanelVar
{
	private $request_var = array();

	function __construct()
	{
	}

	function set_request_var($key, $default_value)
	{
		$this->request_var[$key] = request_var($key, $default_value);
	}

	function get_request_var($key)
	{
		return $this->request_var[$key];
	}
}