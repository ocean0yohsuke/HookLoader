<?php

class phpBB3_HookLoaderAdmAPI_Template_Setting extends phpBB3_HookLoaderAdmAPI_Template_Base
{
	private $display_vars = array();
	
	function main()
	{
		global $template;

		$template->assign_vars(array(
			'S_TEMPLATETYPE'	=> 'setting',
		));
	}

	function set_display_vars($display_vars)
	{
		$this->display_vars = $display_vars;
	}
	
	function create_body()
	{
		global $template, $user;

		$submit = (isset($_POST['submit'])) ? TRUE : FALSE;

		$form_key = 'setting';
		add_form_key($form_key);

		$old_config = $this->Plugin->config;
		$new_config = (isset($_REQUEST['config'])) ? utf8_normalize_nfc(request_var('config', array('' => ''), TRUE)) : array();
		$config = (sizeof($new_config))? $new_config : $old_config;

		$form_error = $notice = array();

		// We validate the complete config if whished
		validate_config_vars($this->display_vars, $config, $form_error);

		if ($submit && !check_form_key($form_key))
		{
			$form_error[] = $user->lang['FORM_INVALID'];
		}

		if ($submit)
		{
			if (sizeof($form_error))
			{
				$template->assign_vars(array(
					'S_ERROR'		=> TRUE,
					'ERROR_MSG'	=> implode('<br />', $form_error),
				));
			}
			else
			{
				$notice[] = $user->lang['SETTING_SUBMIT_SUCCESS'];

				$template->assign_vars(array(
					'S_NOTICE'		=> TRUE,
					'NOTICE_MSG'	=> implode('<br />', $notice),
				));
			}

			$this->set_config($config);
		}

		// Output relevant page
		foreach ($this->display_vars as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === FALSE)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== FALSE)
			{
				$template->assign_block_vars('options', array(
					'S_LEGEND'	=> TRUE,
					'LEGEND'	=> (isset($this->Plugin->lang[$vars])) ? $this->Plugin->lang[$vars] : $vars,
				));

				continue;
			}

			$type = explode(':', $vars['type']);

			$l_explain = '';
			if ($vars['explain'] && isset($vars['lang_explain']))
			{
				$l_explain = (isset($this->Plugin->lang[$vars['lang_explain']])) ? $this->Plugin->lang[$vars['lang_explain']] : $vars['lang_explain'];
			}
			else if ($vars['explain'])
			{
				$l_explain = (isset($this->Plugin->lang[$vars['lang'] . '_EXPLAIN'])) ? $this->Plugin->lang[$vars['lang'] . '_EXPLAIN'] : '';
			}

			$content = build_cfg_template($type, $config_key, $config, $config_key, $vars);

			if (empty($content))
			{
				continue;
			}

			$template->assign_block_vars('options', array(
				'KEY'				=> $config_key,
				'TITLE'			=> (isset($this->Plugin->lang[$vars['lang']])) ? $this->Plugin->lang[$vars['lang']] : $vars['lang'],
				'S_EXPLAIN'		=> $vars['explain'],
				'TITLE_EXPLAIN'	=> $l_explain,
				'CONTENT'			=> $content,
			));

			unset($this->display_vars[$config_key]);
		}
	}
	private function set_config(Array $config)
	{
		foreach ($config as $key => $val)
		{
			$this->Plugin->set_config($key, $val);
		}
	}
		
}