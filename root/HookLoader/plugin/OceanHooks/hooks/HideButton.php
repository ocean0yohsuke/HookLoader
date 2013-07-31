<?php

class phpBB3_HookLoaderPluginHook_OceanHooks_HideButton
{
	public $API;
	
	private $forum_id;

	function __construct()
	{
	}

	function template_display()
	{
		if ($this->API->Page()->inBoard())
		{
			$this->forum_id = request_var('f', 0);
			
			if (!isset($this->API->Plugin->config['HideButton_enabled']) || !$this->API->Plugin->config['HideButton_enabled']) {
				return;
			}
			
			switch($this->API->Page()->name())
			{
				case 'viewforum.php' :
					$this->hide_post_button();
					break;
				case 'viewtopic.php' :
					$this->hide_post_button();
					$this->hide_quote_button();
					break;
				case 'posting.php' :
					$this->hide_inline_button();
					break;
				default :
			}
		}
	}

	/**
	 * Hide post button and reply button for users who aren't allowed to do the action.
	 */
	private function hide_post_button()
	{
		if (!$this->API->Plugin->config['HideButton_post_enabled'])
		{
			return;
		}

		global $template, $auth;

		if (isset($template->_tpldata['.'][0]['S_DISPLAY_POST_INFO']) && $template->_tpldata['.'][0]['S_DISPLAY_POST_INFO'])
		{
			$template->_tpldata['.'][0]['S_DISPLAY_POST_INFO'] = $auth->acl_get('f_post', $this->forum_id);
		}
		if (isset($template->_tpldata['.'][0]['S_DISPLAY_REPLY_INFO']) && $template->_tpldata['.'][0]['S_DISPLAY_REPLY_INFO'])
		{
			$template->_tpldata['.'][0]['S_DISPLAY_REPLY_INFO'] = $auth->acl_get('f_reply', $this->forum_id);
		}
	}

	/**
	 * Hide quote button for users who aren't allowed permission "f_bbcode".
	 */
	private function hide_quote_button()
	{
		if (!$this->API->Plugin->config['HideButton_quote_enabled'])
		{
			return;
		}

		global $template, $auth;

		if (isset($template->_tpldata['postrow']) && !$auth->acl_get('f_bbcode', $this->forum_id))
		{
			for($i = 0, $size = sizeof($template->_tpldata['postrow']); $i < $size; ++$i)
			{
				$template->_tpldata['postrow'][$i]['U_QUOTE'] = null;
			}
		}
	}

	/**
	 * Hide inline button for users who aren't allowed permission "f_bbcode".
	 */
	private function hide_inline_button()
	{
		if (!$this->API->Plugin->config['HideButton_inline_enabled'])	{
			return;
		}

		global $template, $auth;

		if (isset($template->_tpldata['.']) && !$auth->acl_get('f_bbcode', $this->forum_id))
		{
			$template->_tpldata['.'][0]['S_INLINE_ATTACHMENT_OPTIONS'] = '';
		}
	}
}