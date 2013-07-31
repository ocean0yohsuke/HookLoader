<?php

class phpBB3_HookLoaderPluginHook_OceanHooks_SpamFilter
{
	public $API;
	
	function __construct()
	{
	}

	function phpbb_user_session_handler()
	{
		global $user;
		
		if ($this->API->Page()->inBoard() && $user->data['user_id'] == ANONYMOUS)
		{
			if (!isset($this->API->Plugin->config['SpamFilter_enabled']) || !$this->API->Plugin->config['SpamFilter_enabled'])
			{
				return;
			}
				
			switch($this->API->Page()->name())
			{
				case 'posting.php' :
					$this->Posting();
					break;
				default :
					break;
			}
		}
	}

	private function Posting()
	{
		if (isset($_POST['post']) || isset($_POST['preview']))
		{
			$message	= utf8_normalize_nfc(request_var('message', '', true));
				
			$pattern = $this->API->Plugin->config['SpamFilter_posting_message_preg_pattern'];
			if ($pattern && !preg_match($pattern, $message)) {
				trigger_error($this->API->Plugin->config['SpamFilter_posting_message_error_message']);
			}
			
			$pattern = $this->API->Plugin->config['SpamFilter_posting_message_preg_pattern_NG'];
			if ($pattern && preg_match($pattern, $message)) {
				trigger_error($this->API->Plugin->config['SpamFilter_posting_message_error_message_NG']);
			}
		}
	}
}