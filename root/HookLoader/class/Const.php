<?php

class phpBB3_HookLoaderConst
{
	const VERSION = '2.0.0';
	
	static function hookTypes()
	{
		return array(
			'phpbb_user_session_handler',
			'template_display',
			'append_sid',
			'exit_handler',
		);
	}
}