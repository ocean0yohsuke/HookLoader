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
//'HOOKLOADER_OCEANHOOKS_SIDEMENU_CUSTOMASSET' 	=> '',
//'HOOKLOADER_OCEANHOOKS_SIDEMENU_HIDEBUTTON0'	=> '',
//'HOOKLOADER_OCEANHOOKS_SIDEMENU_SPAMFILTER' 	=> '',

//
// Intro
//
'INTRO_TITLE' 		=> 'はじめに',
'INTRO_TITLE_EXPLAIN' 	=> 'このプラグインは hook の寄せ集めです。各 hook について 有効化/無効化 が可能です。',

//
// Common
//
'GENERAL_SETTINGS'	=> '一般設定',
		
//
// HideButton
//
'OCEANHOOKS_HIDEBUTTON_TITLE'		=> 'HideButton 設定',
'OCEANHOOKS_HIDEBUTTON_TITLE_EXPLAIN'	=> '',
'HIDEBUTTON_ENABLED'			=> 'HideButton を有効にする',
'HIDEBUTTON_ENABLED_EXPLAIN'	=> '',
'HIDEBUTTON_SETTINGS' 		=> 'ボタン設定',
'HIDEBUTTON_POST_ENABLED'		=> '投稿/返信 ボタンを隠す',
'HIDEBUTTON_POST_ENABLED_EXPLAIN'	=> 'フォーラム閲覧ページとトピック表示ページにおいて、ゲストに対して（投稿および返信のパーミッションが与えられていない場合に）投稿/返信 ボタンを表示しません',
'HIDEBUTTON_QUOTE_ENABLED'		=> '引用ボタンを隠す',
'HIDEBUTTON_QUOTE_ENABLED_EXPLAIN'	=> 'トピック表示ページにおいて、BBCode 使用のパーミッションが与えられていないグループに対して引用ボタンを表示しません',
'HIDEBUTTON_INLINE_ENABLED'			=> 'インライン表示ボタンを隠す',
'HIDEBUTTON_INLINE_ENABLED_EXPLAIN'	=> '記事投稿ページにおいて、 BBCode 使用のパーミッションが与えられていないグループに対して添付ファイルのインライン表示ボタンを表示しません',

//
// SpamFilter
//
'OCEANHOOKS_SPAMFILTER_TITLE'		=> 'SpamFilter 設定',
'OCEANHOOKS_SPAMFILTER_TITLE_EXPLAIN'	=> '',
'SPAMFILTER_ENABLED'				=> 'SpamFilter を有効にする',
'SPAMFILTER_ENABLED_EXPLAIN'		=> '',
'SPAMFILTER_POSTING_SETTINGS'		=> '投稿設定',
'SPAMFILTER_POSTING_MESSAGE_PREG_PATTERN'			=> '記事本文の正規表現',
'SPAMFILTER_POSTING_MESSAGE_PREG_PATTERN_EXPLAIN'	=> 'この正規表現パターンに一致<b>しない</b>記事をゲストが投稿した場合、下のエラーメッセージが表示されます。この機能を無効化したい場合は空にしてください。',
'SPAMFILTER_POSTING_MESSAGE_ERROR_MESSAGE'		=> 'エラーメッセージ',
'SPAMFILTER_POSTING_MESSAGE_ERROR_MESSAGE_EXPLAIN'	=> '',
'SPAMFILTER_POSTING_MESSAGE_PREG_PATTERN_NG'		=> '記事本文の正規表現 [NG]',
'SPAMFILTER_POSTING_MESSAGE_PREG_PATTERN_NG_EXPLAIN'	=> 'この正規表現パターンに一致<b>する</b>記事をゲストが投稿した場合、下のエラーメッセージが表示されます。この機能を無効化したい場合は空にしてください。',
'SPAMFILTER_POSTING_MESSAGE_ERROR_MESSAGE_NG'		=> 'エラーメッセージ [NG]',
'SPAMFILTER_POSTING_MESSAGE_ERROR_MESSAGE_NG_EXPLAIN'	=> '',

//
// CustomAsset
//
'OCEANHOOKS_CUSTOMASSET_TITLE'		=> 'CustomAsset 設定',
'OCEANHOOKS_CUSTOMASSET_TITLE_EXPLAIN'	=> 'この Hook を利用するには phpBB3 ルートディレクトリ直下に CustomAsset ディレクトリを作成する必要があります。',
'CUSTOMASSET_ENABLED'			=> 'CustomAsset を有効にする',
'CUSTOMASSET_ENABLED_EXPLAIN'		=> '',
'CUSTOMASSET_SETTINGS' 			=> 'Asset 設定',
'CUSTOMASSET_LANG_ENABLED' 			=> 'language を有効にする',
'CUSTOMASSET_LANG_ENABLED_EXPLAIN' 	=> 'CustomAsset/language ディレクトリに phpBB3 の言語ファイル を置くことで phpBB3 の表示文章が影響を受けます。但し以下のファイルは影響を受けません。<br />acp/***.php, <br />email/***.php, <br />mods/***.php, <br />help_bbcode.php, <br />help_faq.php, <br />search_ignore_words.php, <br />search_synonyms.php',
'CUSTOMASSET_TEMPLATE_ENABLED' 		=> 'styles を有効にする',
'CUSTOMASSET_TEMPLATE_ENABLED_EXPLAIN' 	=> 'CustomAsset/styles ディレクトリに phpBB3 のスタイルを置くことで phpBB3 のスタイルがそれに置き換わります。但し置き換わるのは（未継承の）テンプレートのみです。<br />イメージセット・テーマ・継承済みテンプレートはいずれも置き換わらない点にご注意ください。',

));