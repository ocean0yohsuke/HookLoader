<?php
/**
 * DO NOT CHANGE!
 */
if(!defined('IN_PHPBB'))
{
	exit;
}

if (isset($phpbb_root_path)
 && !(defined('IN_INSTALL') && IN_INSTALL == true))
{
	require_once ($phpbb_root_path . "HookLoader/autoload.php");

	try {
		$phpBB3_HookLoader = new phpBB3_HookLoaderMain();
		if (defined('ADMIN_START'))	{
			$phpBB3_HookLoader->acpi();
		}
		if ($phpBB3_HookLoader->isEnabled())
		{
			$phpBB3_HookLoader_Hook = $phpBB3_HookLoader->Hook();
			if ($phpBB3_HookLoader->isEnabled('phpbb_user_session_handler')) {
				$phpbb_hook->register('phpbb_user_session_handler', array(new phpBB3_hook_HookLoader($phpBB3_HookLoader_Hook, 'phpbb_user_session_handler'), 'main'));
			}
			if ($phpBB3_HookLoader->isEnabled('template_display')) {
				$phpbb_hook->register(array('template', 'display'), array(new phpBB3_hook_HookLoader($phpBB3_HookLoader_Hook, 'template_display'), 'main'));
			}
			if ($phpBB3_HookLoader->isEnabled('append_sid')) {
				$phpbb_hook->register('append_sid', array(new phpBB3_hook_HookLoader($phpBB3_HookLoader_Hook, 'append_sid'), 'main'));
			}
			if ($phpBB3_HookLoader->isEnabled('exit_handler')) {
				$phpbb_hook->register('exit_handler', array(new phpBB3_hook_HookLoader($phpBB3_HookLoader_Hook, 'exit_handler'), 'main'));
			}
		}
		unset($phpBB3_HookLoader);
		unset($phpBB3_HookLoader_Hook);
	}
	catch (ObjectFileSystemException $e) {
		if (defined('DEBUG')) {
			$e->getException();
		} else {
			trigger_error('[ObjectFileSystem Error] ' . $e->getMessage(), E_USER_ERROR);
		}
	}
	catch (MethodFileSystemException $e) {
		if (defined('DEBUG')) {
			$e->getException();
		} else {
			trigger_error('[MethodFileSystem Error] ' . $e->getMessage(), E_USER_ERROR);
		}
	}
	catch (phpBB3_HookLoaderException $e) {
		$e->getException();
	}
}

class phpBB3_hook_HookLoader
{
	private $Hook;
	private $hook_type;

	function __construct($Hook, $hook_type)
	{
		$this->Hook = $Hook;
		$this->hook_type = $hook_type;
	}

	function main()
	{
		try {
			$this->Hook->run($this->hook_type);
		}
		catch (ObjectFileSystemException $e) {
			if (defined('DEBUG')) {
				$e->getException();
			} else {
				trigger_error('[ObjectFileSystem Error] ' . $e->getMessage(), E_USER_ERROR);
			}
		}
		catch (MethodFileSystemException $e) {
			if (defined('DEBUG')) {
				$e->getException();
			} else {
				trigger_error('[MethodFileSystem Error] ' . $e->getMessage(), E_USER_ERROR);
			}
		}
		catch (phpBB3_HookLoaderException $e) {
			$e->getException();
		}
	}
}