<?php

class phpBB3_HookLoaderAdmMain_ACP extends ObjectFileSystemFile
{
	function main()
	{
		global $phpbb_root_path, $phpbb_hook;

		if (isset($phpbb_root_path) && $phpbb_root_path == './../')	{
			$phpbb_hook->register('phpbb_user_session_handler', array($this, 'load_lang'));
			$phpbb_hook->register(array('template', 'display'), array($this, 'create_sidemenu'));
		}
	}

	function load_lang()
	{
		global $user;

		$lang = array();
		if (is_file(PHPBB_HOOKLOADER_ROOT_PATH . "language/{$user->lang_name}.php")) {
			include PHPBB_HOOKLOADER_ROOT_PATH . "language/{$user->lang_name}.php";
		} else {
			include PHPBB_HOOKLOADER_ROOT_PATH . "language/en.php";
		}
		$user->lang += $lang;
	}
	
	function create_sidemenu()
	{
		global $user;

		if ((int)$user->data['user_type'] !== USER_FOUNDER) {
			return;
		}

		global $phpbb_root_path;
		global $template, $db;

		$module_id = request_var('i', '');
			
		if (preg_match("/^[0-9]+$/", $module_id))
		{
			$sql = 'SELECT *
				FROM ' . MODULES_TABLE . "
				WHERE module_class = 'acp'
					AND module_id = $module_id";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			if (!$row)	{
				trigger_error($user->lang['NO_MODULE'], E_USER_WARNING);
			}
			$module_id = $row['module_langname'];
		}

		if (in_array($module_id, array('', 'ACP_CAT_GENERAL')))
		{
			$template->assign_block_vars('l_block1', array(
				'S_SELECTED'	=> TRUE,
			));
			$template->assign_block_vars('l_block1.l_block2', array(
				'L_TITLE'		=> $user->lang['HOOKLOADER_TITLE'],
			));
			$template->assign_block_vars('l_block1.l_block2.l_block3', array(
				'L_TITLE'		=> $user->lang['HOOKLOADER_INDEX'],
				'U_TITLE'		=> append_sid($phpbb_root_path . 'HookLoader/adm/index.php'),
			));
		}
	}
}