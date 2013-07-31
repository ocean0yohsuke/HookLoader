<?php

class phpBB3_HookLoaderAdmAPI_Template_Requirement extends phpBB3_HookLoaderAdmAPI_Template_Base
{
	function main()
	{
		global $template;

		$template->assign_vars(array(
			'S_TEMPLATETYPE'	=> 'requirement',
		));
	}
	
	function create_body_legend($title, $title_explain = '')
	{
		global $template;

		$template->assign_block_vars('checks', array(
			'S_LEGEND'			=> TRUE,
			'LEGEND'			=> $title,
			'LEGEND_EXPLAIN'	=> $title_explain,
		));
	}

	function create_body_requirement($title, $result, $title_explain = '')
	{
		global $template;

		$s_explain = ($title_explain === '')? FALSE : TRUE;
		
		$template->assign_block_vars('checks', array(
			'S_LEGEND'			=> FALSE,
			'TITLE'			=> $title,
			'TITLE_EXPLAIN'	=> $title_explain,
			'RESULT'			=> $result,
			'S_EXPLAIN'		=> $s_explain,
		));
	}
}