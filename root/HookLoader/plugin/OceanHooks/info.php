<?php

$plugin = array(
	'version' 				=> '1.0.1',
	'PHP-version' 		=> '5.1.2',
	'phpBB-version' 		=> '3.0.10',
	'HookLoader-version' => '2.0.0',
	'description'			=> '様々な hook の寄せ集めです',
	'author-notes'		=> '---',
	'author'				=> array(
		'name' 	=> 'ocean',
		'www'		=> 'http://www.bbmods.info/bbs/',),
	'licence' 				=> 'http://opensource.org/licenses/gpl-license.php GNU General Public License v2',
);
$adm['menu_order'] = array(
	'CustomAsset' => 1,
	'HideButton'	=> 2,
	'SpamFilter'	=> 3,
);
$config_default = array(
	'enabled' 					=> 1,
	
	'CustomAsset_enabled'				=> 1,
	'CustomAsset_lang_enabled'			=> 1,
	'CustomAsset_template_enabled'		=> 1,
	
	'HideButton_enabled'			=> 1,
	'HideButton_post_enabled'	=> 1,
	'HideButton_quote_enabled'	=> 1,
	'HideButton_inline_enabled'	=> 1,
	
	'SpamFilter_enabled'									=> 1,
	'SpamFilter_posting_message_preg_pattern'			=> '#[一-龠ぁ-んァ-ヾ゛々]+#u',
	'SpamFilter_posting_message_error_message'		=> '記事本文に日本語が 1 文字も含まれていません',
	'SpamFilter_posting_message_preg_pattern_NG'		=> '',
	'SpamFilter_posting_message_error_message_NG'		=> '',
);