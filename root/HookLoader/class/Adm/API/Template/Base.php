<?php

class phpBB3_HookLoaderAdmAPI_Template_Base extends phpBB3_HookLoaderAdmAPI_Base
{
	function create_head($title, $title_explain = '')
	{
		global $template;
	
		$template->assign_vars(array(
			'TITLE'			=> $title,
			'TITLE_EXPLAIN'	=> $title_explain,
		));
	}
}
	