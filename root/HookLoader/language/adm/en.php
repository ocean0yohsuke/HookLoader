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
'SIDEMENU_INTRO' 			=> '- はじめに -',
'SIDEMENU_PLUGININFO' 	=> '- プラグイン情報 -',
'SIDEMENU_SETUP' 			=> '- セットアップ -',
'SIDEMENU_UNSETUP' 		=> '- アンセットアップ -',

// 
// Common
//
'HOOKLOADER_INDEX' 	=> 'HookLoaderトップ',
'ADMIN_INDEX'			=> 'AdminCPトップ',
'NO_ADMIN'				=> 'ユーザー認証されていないため、AdminCP へ入室できません',
'NO_FOUNDER'			=> 'ウェブマスターでないためこのページへアクセスできません',
'NO_MODULE'			=> 'モジュールが見つかりません',
'FORM_INVALID'		=> '送信されたフォームが不正です',
'RUN'					=> '実行',
'WARNING'				=> '警告',

//
// Setup & Unsetup
//
'SETUPPANEL_SETUP_TITLE'			=> 'セットアップ',
'SETUPPANEL_SETUP_MESSAGE'	 	=> 'ここでは %1$s %2$s のセットアップを実行できます。セットアップを実行したい場合は下の実行ボタンをクリックしてください。',
'SETUPPANEL_UNSETUP_TITLE' 		=> 'アンセットアップ',
'SETUPPANEL_UNSETUP_MESSAGE'	=> 'ここでは %1$s %2$s のアンセットアップを実行できます。アンセットアップを実行したい場合は下の実行ボタンをクリックしてください。',
'SETUPPANEL_CONFIGKEY_ADDED'	=> 'コンフィグデータ %s を追加しました',
'SETUPPANEL_CONFIGKEY_UPDATED'	=> 'コンフィグデータ %1$s の値を %2$s に更新しました',
'SETUPPANEL_CONFIGKEY_DELETED'	=> 'コンフィグデータ %s を削除しました',	
'SETUPPANEL_SETUP_SUCCESSED'	=> '%1$s %2$s のセットアップに成功しました',
'SETUPPANEL_UNSETUP_SUCCESSED'	=> '%1$s %2$s のアンセットアップに成功しました',
'GOTO_MENU'						=> 'メニューへ',

//
// Setting
//
'SETTING_SUBMIT_SUCCESS'		=> '設定の更新に成功しました',

//
// Intro
//
'INTRO_TITLE'		=> 'はじめに',

//
// Plugin Info
//
'PlUGININFO_TITLE' 						=> 'プラグイン情報',
'PLUGININFO_LEGEND_PLUGIN'				=> 'About this Plugin',
'PLUGININFO_PLUGIN_TITLE' 				=> 'Title',
'PLUGININFO_PLUGIN_DESCRIPTION'			=> 'Description',
'PLUGININFO_PLUGIN_VERSION'				=> 'Version',
'PLUGININFO_PLUGIN_PHPVERSION'			=> 'PHP Version (required)',
'PLUGININFO_PLUGIN_PHPEXTENSIONS'		=> 'PHP Extensions (required)',
'PLUGININFO_PLUGIN_PHPBBVERSION'		=> 'phpBB Version (required)',
'PLUGININFO_PLUGIN_HOOKLOADERVERSION'	=> 'HookLoader Version (required)',
'PLUGININFO_PLUGIN_AUTHORNOTES' 		=> 'Author notes',
'PLUGININFO_LEGEND_AUTHOR' 				=> 'Author',
'PLUGININFO_AUTHOR_NAME' 				=> 'Name',
'PLUGININFO_AUTHOR_WWW' 					=> 'WWW',

//
// Error
//
'SIDEMENU_ERROR_EMPTY'		=> 'エラー：サイドメニューが指定されていません',
'SIDEMENU_ERROR_INVALID'		=> 'エラー：サイドメニューの値が不正です',
'PHPVERSION_IS_TOO_LOW'				=> 'このプラグインをセットアップするには PHP のバージョンが %s 以上である必要があります',
'HOOKLOADERVERSION_IS_TOO_LOW'		=> 'このプラグインをセットアップするには HookLoader のバージョンが %s 以上である必要があります',
'PHPBBVERSION_IS_TOO_LOW'			=> 'このプラグインをセットアップするには phpBB のバージョンが %s 以上である必要があります',
'PHPBEXTENSION_IS_NOT_FOUND'			=> 'このプラグインをセットアップするには PHP拡張モジュール %s が必要です',
'HOOKLOADER_ISNOT_SETUP'					=> 'HookLoader がセットアップされていないためこのプラグインの Hook は無効です。この Hook を有効にするには HookLoader のセットアップを完了させる必要があります。',
'HOOKLOADER_ISNOT_ENABLED'				=> 'HookLoader が無効化されているためこのプラグインの Hook は無効です。この Hook を有効にするには HookLoader を有効化する必要があります。',

));