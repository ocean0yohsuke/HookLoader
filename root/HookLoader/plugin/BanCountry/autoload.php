<?php
define('PHPBB_HOOKLOADER_BANCOUNTRY_ROOT_PATH', PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "BanCountry/");

// The version of PHP must be 5.1.2 or higher
spl_autoload_register('phpBB3_BanCountry_autoload');

function phpBB3_BanCountry_autoload($class_name)
{
	$class_list = array(
		'phpBB3_BanCountryBase' => 0,
		'phpBB3_BanCountryClient' => 0,
		'phpBB3_BanCountryDB' => 0,
		'phpBB3_BanCountryException' => 0,
		'phpBB3_BanCountryIP' => 0,
		'phpBB3_BanCountryMain' => 0,
	);
	if (isset($class_list[$class_name])) {
		$base_name = preg_replace('#^phpBB3_BanCountry#', '', $class_name); 
		include_once (PHPBB_HOOKLOADER_BANCOUNTRY_ROOT_PATH . "class/{$base_name}.php");
	}
}