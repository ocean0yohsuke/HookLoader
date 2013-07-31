<?php

class phpBB3_HookLoaderPluginAdm_HookLoader_Cache
{
	public $API;
	
	function main()
	{
		global $template, $user;

		$user->add_lang('acp/common');

		$action = request_var('action', '');

		if ($action)
		{
			if (!confirm_box(TRUE))
			{
				switch ($action)
				{
					case 'purge_cache':
						$confirm = TRUE;
						$confirm_lang = 'PURGE_CACHE_CONFIRM';
						break;
					default:
						$confirm = TRUE;
						$confirm_lang = 'CONFIRM_OPERATION';
						break;
				}

				if ($confirm)
				{
					confirm_box(FALSE, $user->lang[$confirm_lang], build_hidden_fields(array(
						'tabmenu'		=> 'HookLoader',
						'sidemenu'		=> 'Cache',
						'action'		=> $action,
					)));
				}
			}
			else
			{
				switch ($action)
				{
					case 'purge_cache':
						phpBB3_HookLoaderAdapter::purge_cache();
						$template->assign_vars(array(
							'S_NOTICE'		=> TRUE,
							'NOTICE_MSG'	=> $user->lang['LOG_PURGE_CACHE']
						));
						break;
				}
			}
		}

		$Panel = $this->API->Template()->Custom();
		$Panel->create_head($this->API->Plugin->lang['CACHE'], $this->API->Plugin->lang['CACHE_EXPLAIN']);
		$Panel->set_template('Cache.html');
		$c_template = $Panel->get_template();

		$c_template->assign_vars(array(
			'U_ACTION' => append_sid($this->API->Page()->root_url(), ""),
		));

		$Panel->create_body();
	}
}
