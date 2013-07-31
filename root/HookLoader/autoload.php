<?php

define('PHPBB_HOOKLOADER_ROOT_PATH', $phpbb_root_path . "HookLoader/");
define('PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH', $phpbb_root_path . "HookLoader/plugin/");

// The version of PHP must be 5.1.2 or higher
spl_autoload_register('phpBB3_HookLoader_autoload');

function phpBB3_HookLoader_autoload($class_name)
{
	$class_list = array(
		'phpBB3_HookLoaderAdapter' => 0,
		'phpBB3_HookLoaderAPI' => 0,
		'phpBB3_HookLoaderConst' => 0,
		'phpBB3_HookLoaderException' => 0,
		'phpBB3_HookLoaderMain' => 0,
		'phpBB3_HookLoaderPlugin' => 0,
		'phpBB3_HookLoaderUtil' => 0,
	);
	if (isset($class_list[$class_name])) {
		$base_name = preg_replace('#^phpBB3_HookLoader#', '', $class_name); 
		include_once (PHPBB_HOOKLOADER_ROOT_PATH . "class/{$base_name}.php");
	}
}