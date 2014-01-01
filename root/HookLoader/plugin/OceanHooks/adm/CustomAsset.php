<?php

class phpBB3_HookLoaderPluginAdm_OceanHooks_CustomAsset
{
	function main()
	{
		$Panel = $this->API->Template()->Setting();
		$Panel->create_head($this->API->Plugin->lang['OCEANHOOKS_CUSTOMASSET_TITLE'], $this->API->Plugin->lang['OCEANHOOKS_CUSTOMASSET_TITLE_EXPLAIN']);
		
		/**
		*	Validation types are:
		*		string, int, bool,
		*		script_path (absolute path in url - beginning with / and no trailing slash),
		*		rpath (relative), rwpath (realtive, writable), path (relative path, but able to escape the root), wpath (writable)
		*/
		$Panel->set_display_vars(array(
			'legend1' => 'GENERAL_SETTINGS',
				'enabled' => array('lang' => 'CUSTOMASSET_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
			'legend2' => 'CUSTOMASSET_SETTINGS',
				'CustomAsset_lang_enabled'		=> array('lang' => 'CUSTOMASSET_LANG_ENABLED',		'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'CustomAsset_template_enabled'	=> array('lang' => 'CUSTOMASSET_TEMPLATE_ENABLED',	'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
		));
		$Panel->create_body();
	}
}
