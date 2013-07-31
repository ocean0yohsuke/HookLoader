<?php

$plugin = array(
	'version' 				=> phpBB3_HookLoaderConst::VERSION,
	'PHP-version' 		=> '5.1.2',
	//'PHP-extensions'		=> array(),
	'phpBB-version' 		=> '3.0.11',
	'HookLoader-version' => '2.0.0',
	'description'			=> 'このプラグインは HookLoader の一部です。このプラグインをセットアップするまで他のプラグインの hook は有効化されません。',
	'author-notes'		=> '---',
	'author'				=> array(
		'name' 		=> 'ocean',
		'www'			=> 'http://www.bbmods.info/bbs/',),
	'licence' 				=> 'http://opensource.org/licenses/gpl-license.php GNU General Public License v2',
);
$adm['menu_order'] = array(
	'Cache' 	=> 1,
	'Config' 	=> 2,
);
$config_default = array(
	'enabled' 									=> 1,
	'phpbb_user_session_handler_enabled'	=> 1,
	'template_display_enabled'				=> 1,
	'append_sid_enabled'						=> 1,
	'exit_handler_enabled'					=> 1,					
);
