<?php

class phpBB3_HookLoaderAdmMain_HLP extends ObjectFileSystemFile
{
	public $lang;
	
	function main()
	{
		$this->boot();
		$this->load_lang();
		$this->Panel();
		$this->output();
	}

	private function load_lang()
	{
		global $user;
		
		$lang = array();
		if (is_file(PHPBB_HOOKLOADER_ROOT_PATH . "language/adm/{$user->lang_name}.php")) {
			include PHPBB_HOOKLOADER_ROOT_PATH . "language/adm/{$user->lang_name}.php";
		} else {
			include PHPBB_HOOKLOADER_ROOT_PATH . "language/adm/en.php";
		}

		$user->lang += $lang;
		$this->lang = $lang;
	}	
	
	private function boot()
	{
		global $phpbb_root_path, $phpEx;
		global $template, $user, $auth;
		global $phpbb_admin_path;

		// Start session management
		$user->session_begin();
		$auth->acl($user->data);
		$user->setup();

		// Is user any type of admin? No, then stop here, each script needs to
		// check specific permissions but this is a catchall
		if (!$auth->acl_get('a_'))
		{
			throw new phpBB3_HookLoaderException($user->lang['NO_ADMIN']);
		}

		if ((int)$user->data['user_type'] !== USER_FOUNDER)
		{
			throw new phpBB3_HookLoaderException($user->lang['NO_FOUNDER']);
		}

		// Have they authenticated (again) as an admin for this session?
		if (!isset($user->data['session_admin']) || !$user->data['session_admin'])
		{
			throw new phpBB3_HookLoaderException($user->lang['HOOKLOADER_LOGIN_ADMIN_CONFIRM']);
		}

		// We define the admin variables now, because the user is now able to use the admin related features...
		define('IN_ADMIN', TRUE);
		$phpbb_admin_path = (defined('PHPBB_ADMIN_PATH')) ? PHPBB_ADMIN_PATH : '../../../adm/';

		// setup template
		$template_path 			= $phpbb_root_path . 'HookLoader/adm/style';
		$template_name 			= 'HookLoaderAdm';
		$fallback_template_path	= FALSE;
		$template->set_custom_template($template_path, $template_name, $fallback_template_path);

		include_once $phpbb_root_path . 'HookLoader/adm/includes/functions.php';
	}
	
	private function output()
	{
		global $phpbb_root_path, $phpEx;
		global $user, $template;

		// common
		$template->assign_vars(array(
			'U_HOOKLOADER_INDEX'	=> append_sid($phpbb_root_path . 'HookLoader/adm/index.php'),
			'U_ADM_INDEX' 		=> append_sid($phpbb_root_path . 'adm/index.' . $phpEx),
		));

		// Output page
		page_header('ACP &bull; HookLoader');
		$template->set_filenames(array(
			'body' => 'start.html')
		);
		page_footer();
	}
}