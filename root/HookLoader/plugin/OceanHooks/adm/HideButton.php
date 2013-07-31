<?php

class phpBB3_HookLoaderPluginAdm_OceanHooks_HideButton
{
	function main()
	{
		$Panel = $this->API->Template()->Setting();
		$Panel->create_head($this->API->Plugin->lang['OCEANHOOKS_HIDEBUTTON_TITLE'], $this->API->Plugin->lang['OCEANHOOKS_HIDEBUTTON_TITLE_EXPLAIN']);

		/**
		*	Validation types are:
		*		string, int, bool,
		*		script_path (absolute path in url - beginning with / and no trailing slash),
		*		rpath (relative), rwpath (realtive, writable), path (relative path, but able to escape the root), wpath (writable)
		*/
		$Panel->set_display_vars(array(
			'legend1'		=> 'GENERAL_SETTINGS',
				'HideButton_enabled' => array('lang' => 'HIDEBUTTON_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
			'legend2'		=> 'HIDEBUTTON_SETTINGS',
				'HideButton_post_enabled'	=> array('lang' => 'HIDEBUTTON_POST_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'HideButton_quote_enabled'	=> array('lang' => 'HIDEBUTTON_QUOTE_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'HideButton_inline_enabled'	=> array('lang' => 'HIDEBUTTON_INLINE_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
		));
		$Panel->create_body();
	}
}
