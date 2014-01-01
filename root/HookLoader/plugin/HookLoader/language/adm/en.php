<?php
if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
'HOOKLOADER_SIDEMENU_CONFIG' 	=> 'コンフィグ',
'HOOKLOADER_SIDEMENU_CACHE' 	=> 'キャッシュ',

//
// Config
//
'CONFIG' 		=> 'コンフィグ',
'CONFIG_EXPLAIN'	=> 'ここでは HookLoader のコンフィグを設定できます。',
'GENERAL_SETTINGS'			=> '一般設定',
'HOOKLOADER_ENABLED'			=> 'HookLoader を有効にする',
'HOOKLOADER_ENABLED_EXPLAIN'	=> '無効にした場合、プラグインの hook は全て無効化されます',
'HOOK_SETTINGS'					=> 'フック設定',
'PHPBB_USER_SESSION_HANDLER_ENABLED'		=> 'phpbb_user_session_handler を有効にする',
'PHPBB_USER_SESSION_HANDLER_ENABLED_EXPLAIN'	=> '',
'TEMPLATEDISPLAY_ENABLED'		=> 'template_display を有効にする',
'TEMPLATEDISPLAY_ENABLED_EXPLAIN'	=> '',
'APPENDSID_ENABLED'			=> 'append_sid を有効にする',
'APPENDSID_ENABLED_EXPLAIN'		=> '',
'EXITHANDLER_ENABLED'		=> 'exit_handler を有効にする',
'EXITHANDLER_ENABLED_EXPLAIN'	=> '',

//
// Cache
//
'CACHE' 					=> 'キャッシュ',
'CACHE_EXPLAIN'				=> '',
'HOOKLOADER_STATISTIC_RESYNC_OPTIONS'	=> 'データの同期',
'HOOKLOADER_PURGE_CACHE'			=> 'キャッシュの消去',
'HOOKLOADER_PURGE_CACHE_EXPLAIN'		=> 'HookLoader のキャッシュ情報を全て消去します。テンプレートキャッシュファイルと hook キャッシュファイルの消去を含みます。',

//
// Intro
//
'HOOKLOADER_INTRO_TITLE' => 'はじめに',

));