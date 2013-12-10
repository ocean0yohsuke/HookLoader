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
//'HOOKLOADER_IPTOCOUNTRY_SIDEMENU_IPTOCOUNTRY' 	=> '',
//'HOOKLOADER_IPTOCOUNTRY_SIDEMENU_BANCOUNTRY'	=> '',

//
// Common
//
'RETURN_PAGE'			=> '%s元のページに戻る%s',
'FORM_INVALID'		=> '送信されたフォームが不正です',

//
// Intro
//
'INTRO_TITLE' 		=> 'はじめに',
'INTRO_TITLE_EXPLAIN' 	=> 'explain',

//
// Config
//
'CONFIG_TITLE' 		=> 'Config',
'CONFIG_TITLE_EXPLAIN' 	=> 'ここでは BanCountry のコンフィグ設定を変更できます',
'GENERAL_SETTINGS' 		=> '一般設定',
'ENABLE_BANCOUNTRY' 		=> 'BanCountry を有効にする',
'ENABLE_BANCOUNTRY_EXPLAIN'	=> '有効にした場合、国名リストとリストタイプ（許可/拒否）を基にこのサイトへアクセスするユーザーを制限します',

'BAN_SETTINGS' 			=> 'Ban 設定',
'CHECK_REGISTRATION_IP' 		=> 'ユーザー登録IP も判定する',
'CHECK_REGISTRATION_IP_EXPLAIN'	=> '有効にした場合、セッションIP だけでなくユーザー登録した際の IPアドレス に対しても国の割り出しを試み、その国がアクセス禁止されているどうかを判定します。ゲストとボットに効果はありません。',

//
// IPtoCountry
//
'IPTOCOUNTRY_TITLE' 		=> 'IP to Country',
'IPTOCOUNTRY_TITLE_EXPLAIN' => 'ここでは IPアドレス から CC(国名コード） を割り出せます。',
'IPTOCOUNTRY_CC' 		=> 'CC',
'IPTOCOUNTRY_IPADDRESS'	=> 'IP',


//
// CountryList
//
'COUNTRYLIST_TITLE' 		=> 'Country List',
'COUNTRYLIST_TITLE_EXPLAIN' => 'ここでは国名リストを設定できます。リストタイプを "許可" か "拒否" のどちらにするか決定してください。<br />あなた自身の国をアクセス禁止の対象に含む設定にすると BanCountry は自動的に無効化されます。もしその設定のまま Config ページで BanCountry を有効化するとあなた自身がアクセス禁止されますのでご注意ください。FTPクライアントからファイル HookLoader/plugin/BanCountry/hooks/BanCountry.php を削除することでいつでも BanCountry のアクセス禁止機能を無効化できます。',

'COUNTRYLIST_LISTTYPE'	=> 'リストタイプ',
'LISTTYPE'			=> 'リストタイプ',
'LISTTYPE_EXPLAIN'		=> '"許可" を選択した場合、国名リストに登録されている国からのアクセスのみ許可します。"拒否" を選択した場合、国名リストに登録されている国からのアクセスを拒否します。',
'ALLOW'			=> '許可',
'DENY'				=> '拒否',

'COUNTRYLIST_ADD'	=> 'リストへ追加',
'ISO_3166_2'		=> 'ISO 3166 二字国名コード',
'ISO_3166_2_EXPLAIN'	=> 'リストに追加したい国の二字国名コードを一行につき一つ入力してください。例えば日本の場合、"JP" が二字国名コードになります。<br /> 詳しくは http://ja.wikipedia.org/wiki/ISO_3166-1 をご覧ください。',

'COUNTRYLIST_DELETE'	=> 'リストから削除',
'COUNTRYLIST'		=> '国名リスト',
'COUNTRYLIST_EXPLAIN'=> '削除したい国を選択してください。複数選択するにはマウスとキーボードをうまく活用してください （SHIFTキー または CTRLキー を押しながらクリック）。',

'BANNED_YOURSELF'	=> 'あなた自身の国をアクセス禁止にしています！ BanCountry を有効化するとあなたは掲示板に一切アクセスできなくなります！',		
'NO_COUNTRIES'	=> '国を入力または選択してください',
'COUNTRYNAME_IS_INVALID'		=> '%s は国名コードとして不正です',
'COUNTRYLIST_LISTTYPE_SUCCESSED'	=> 'リストタイプの設定に成功しました',
'COUNTRYLIST_ADD_SUCCESSED'	=> '国名コードの追加に成功しました',
'COUNTRYLIST_DEL_SUCCESSED'	=> '国名コードの削除に成功しました',
'COUNTRYLIST_DISABLED_THIS'	=> 'あなたの国がアクセス禁止されたため、BanCountry を無効化しました',

//
// UserList
//	
'USERLIST_TITLE' 		=> 'User List',
'USERLIST_TITLE_EXPLAIN' 	=> '',
'USERLIST_INTRO_EXPLAIN' 	=> 'ユーザー数が多い場合、ユーザーリストの作成は非常に時間がかかります。それでも良い場合は下のボタンをクリックしてください。',
'USERLIST_BANNED_USERS'	=> 'アクセス禁止ユーザー',
'USERLIST_ALLOWED_USERS'	=> 'アクセス可能ユーザー',
'USERLIST_REGISTRATIONIP'	=> 'ユーザー登録IP ',
'USERLIST_CC'			=> 'CC',
'USERLIST_POSTS'		=> '記事数',
'USERLIST_JOINED'		=> '登録日時',
'USERLIST_VISITED'		=> '最近の訪問日時',
'USERLIST_RANK'		=> 'ランク',
'USERLIST_LIST_USERS'	=> '%d 人のユーザー',
'USERLIST_OTHERTHAN'		=> '以外',
'USERLIST_ITEMS'		=> '件',

//
// ImportIPRecord
//	
'IMPORTIPRECORD_TITLE' 		=> 'Import IP Record',
'IMPORTIPRECORD_TITLE_EXPLAIN' 	=> '',

));