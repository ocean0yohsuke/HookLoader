<?php

class phpBB3_HookLoaderAdapter
{
	static function purge_cache()
	{
		global $phpbb_root_path;
		global $cache;

		$cache->destroy('config');

		$dirname = $phpbb_root_path . 'cache';
		$iterator = new DirectoryIterator($phpbb_root_path . 'cache');
		foreach ($iterator as $fileinfo)
		{
			if ($fileinfo->isFile())
			{
				$filename = $fileinfo->getFilename();
				if (preg_match('#^(data_HookLoader|ctpl_HookLoader)#i', $filename))
				{
					$cache->remove_file($dirname . '/' . $filename, TRUE);
				}
			}
		}
	}	
}