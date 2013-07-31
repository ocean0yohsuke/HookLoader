<?php

class phpBB3_HookLoaderAdmMain_HLP_PanelPage_error extends phpBB3_HookLoaderAdmMain_HLP_PanelPage_base
{
	function main($error_msg)
	{
		global $template;

		$template->assign_vars(array(
			'S_TEMPLATETYPE'	=> 'custom',
		));

		$contents = '
<div class="errorbox">
	<h3>{L_WARNING}</h3>
	<p>{ERROR_MSG}</p>
</div>
';
		$replace = array(
			'{L_WARNING}' => $this->lang['WARNING'],
			'{ERROR_MSG}' => $error_msg,
		);
		$contents = str_replace(array_keys($replace), array_values($replace), $contents);

		$template->assign_vars(array(
			'CUSTOMTEMPLATE' => $contents,
		));
	}
}