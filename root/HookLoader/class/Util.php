<?php

class phpBB3_HookLoaderUtil
{
	static function ObjectFileSystem($rootPath, $rootNamespace, $public_props=array())
	{
		$FS = new ObjectFileSystem(PHPBB_HOOKLOADER_ROOT_PATH . $rootPath, "phpBB3_HookLoader{$rootNamespace}", defined('DEBUG'), FALSE);
		foreach($public_props as $prop_name => $prop_value)
		{
			$FS->{$prop_name} = $prop_value;
		}
		$FS->make();
		if (!defined('DEBUG')) {
			global $cache;
			$FS_cache = $cache->get("_HookLoader{$rootNamespace}_OFS");
			if (!$FS_cache) 	{
				$FS_cache = $FS->get_cache();
				$cache->put("_HookLoader{$rootNamespace}_OFS", $FS_cache);
			}
			$FS->set_cache($FS_cache);
		}
		return $FS;
	}
	
	static function MethodFileSystem($rootPath, $rootNamespace, $public_props=array())
	{
		$FS = new MethodFileSystem(PHPBB_HOOKLOADER_ROOT_PATH . $rootPath, "phpBB3_HookLoader{$rootNamespace}", defined('DEBUG'), FALSE);
		foreach($public_props as $prop_name => $prop_value)
		{
			$FS->{$prop_name} = $prop_value;
		}
		$FS->make();
		if (!defined('DEBUG')) {
			global $cache;
			$FS_cache = $cache->get("_HookLoader{$rootNamespace}_MFS");
			if (!$FS_cache) 	{
				$FS_cache = $FS->get_cache();
				$cache->put("_HookLoader{$rootNamespace}_MFS", $FS_cache);
			}
			$FS->set_cache($FS_cache);
		}
		return $FS;
	}
}