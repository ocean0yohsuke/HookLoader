<?php
/**
 */
define('IN_PHPBB', TRUE);
define('ADMIN_START', TRUE);
define('NEED_SID', TRUE);

// Include files
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require($phpbb_root_path . 'common.' . $phpEx);
require_once($phpbb_root_path . "HookLoader/autoload.php");

try {
	$phpBB3_HookLoader = new phpBB3_HookLoaderMain();
	$phpBB3_HookLoader->Adm_run();
} 
catch (ObjectFileSystemException $e) {
	if (defined('DEBUG')) {
		$e->getException();
	} else {
		trigger_error('[ObjectFileSystem Error] ' . $e->getMessage(), E_USER_ERROR);
	}
}
catch (phpBB3_HookLoaderException $e) {
	$e->getException();
}

?>