<?php

class phpBB3_HookLoaderPluginAdm_OceanHooks_SpamFilter
{
	function main()
	{
		$Panel = $this->API->Template()->Setting();
		$Panel->create_head($this->API->Plugin->lang['OCEANHOOKS_SPAMFILTER_TITLE'], $this->API->Plugin->lang['OCEANHOOKS_SPAMFILTER_TITLE_EXPLAIN']);
		
		/**
		 *	Validation types are:
		 *		string, int, bool,
		 *		script_path (absolute path in url - beginning with / and no trailing slash),
		 *		rpath (relative), rwpath (realtive, writable), path (relative path, but able to escape the root), wpath (writable)
		 */
		$Panel->set_display_vars(array(
			'legend1'		=> 'GENERAL_SETTINGS',
				'SpamFilter_enabled' => array('lang' => 'SPAMFILTER_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
			'legend2'		=> 'SPAMFILTER_POSTING_SETTINGS',
				'SpamFilter_posting_message_preg_pattern'		=> array('lang' => 'SPAMFILTER_POSTING_MESSAGE_PREG_PATTERN',	'validate' => 'string',	'type' => 'textarea:5:40', 'explain' => true),
				'SpamFilter_posting_message_error_message'	=> array('lang' => 'SPAMFILTER_POSTING_MESSAGE_ERROR_MESSAGE',	'validate' => 'string',	'type' => 'text:60:255', 'explain' => true),
				'SpamFilter_posting_message_preg_pattern_NG'	=> array('lang' => 'SPAMFILTER_POSTING_MESSAGE_PREG_PATTERN_NG',	'validate' => 'string',	'type' => 'textarea:5:40', 'explain' => true),
				'SpamFilter_posting_message_error_message_NG'	=> array('lang' => 'SPAMFILTER_POSTING_MESSAGE_ERROR_MESSAGE_NG',	'validate' => 'string',	'type' => 'text:60:255', 'explain' => true),
		));
		$Panel->create_body();
	}
}
