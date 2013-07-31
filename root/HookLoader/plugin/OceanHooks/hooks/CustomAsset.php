<?php

class phpBB3_HookLoaderPluginHook_OceanHooks_CustomAsset
{
	public $API;
	
	function __construct()
	{
	}

	function phpbb_user_session_handler()
	{
		if (!isset($this->API->Plugin->config['CustomAsset_enabled']) || !$this->API->Plugin->config['CustomAsset_enabled']) {
			return;
		}

		if ($this->API->Page()->inBoard() || $this->API->Page()->inUCP() || $this->API->Page()->inMCP())
		{
			if ($this->API->Plugin->config['CustomAsset_lang_enabled']) {
				$this->load_custom_lang();
			}
			if ($this->API->Plugin->config['CustomAsset_template_enabled']) {
				$this->load_custom_template();
			}
		}
	}

	/**
	 * Load language files
	 * supported 	   :	language/---/---.php
	 * not supported :	language/---/acp
	 * 						language/---/email
	 * 						language/---/mods
	 * 						language/---/help_bbcode.php
	 * 						language/---/help_faq.php
	 */
	function load_custom_lang()
	{
		global $phpbb_root_path, $phpEx;
		global $user;

		$user_lang_path = $user->lang_path;

		$user->lang_path = $phpbb_root_path . 'CustomAsset/language/';

		// common
		$filename = "common.$phpEx";
		if (is_file($phpbb_root_path . "CustomAsset/language/{$user->lang_name}/{$filename}")) {
			$user->add_lang('common');
		}

		// current
		$filename 	= $this->API->Page()->name();
		if (is_file($phpbb_root_path . "CustomAsset/language/{$user->lang_name}/{$filename}")) {
			$user->add_lang(basename($filename, ".$phpEx"));
		}

		$user->lang_path = $user_lang_path;
	}

	/**
	 * Load style files
	 * supported 		： 	styles/---/template/---.html
	 * not supported 	： 	styles/---/theme
	 *						styles/---/imagesets
	 *						styles/---/style.cfg
	 */
	function load_custom_template()
	{
		global $phpbb_root_path;
		global $template, $user;

		if (!$template->orig_tpl_inherits_id)
		{
			$user_style_name = $user->theme['theme_name'];

			$template_path = $phpbb_root_path . "CustomAsset/styles/{$user_style_name}/template";
			if (is_dir($template_path))
			{
				$template_name = $user_style_name;
				$fallback_template_path = $phpbb_root_path . "styles/{$user_style_name}/template";

				$template->set_custom_template($template_path, $template_name, $fallback_template_path);
			}
		}
	}
}