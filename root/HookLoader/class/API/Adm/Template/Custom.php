<?php

class phpBB3_HookLoaderAPIAdm_Template_Custom extends phpBB3_HookLoaderAPIAdm_Template_Base
{
	private $template;
	
	function main()
	{
	}
	
	function set_template($filename)
	{
		global $phpbb_root_path;
		
		$this->template = new template();
		$this->template->set_custom_template($phpbb_root_path . "HookLoader/plugin/{$this->Plugin->plugin_name}/style/adm", "HookLoaderPluginAdm_{$this->Plugin->plugin_name}");
		$this->template->set_filenames(array(
			'body' => $filename,
		));
	}
	
	function get_template()
	{
		return $this->template;
	}
	
	function create_body()
	{
		global $template;
		
		ob_start();
		$this->template->display('body');
		$contents = ob_get_contents();
		ob_end_clean();

		$template->assign_vars(array(
			'CUSTOMTEMPLATE' => $contents,
		));
	}
}