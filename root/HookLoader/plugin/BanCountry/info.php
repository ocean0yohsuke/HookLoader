<?php

$plugin = array(
	'version' 				=> '1.0.0',
	'PHP-version' 		=> '5.2.0',
	'PHP-extensions'		=> array('PDO', 'pdo_sqlite'),
	'phpBB-version' 		=> '3.0.11',
	'HookLoader-version' => '2.0.0',
	'description'			=> '国をアクセス禁止できます',
	'author-notes'		=> 'RIR の IPアドレスレコード のデータは 2013/05/30 のものです。<br />PHP拡張モジュール PDO と pdo_sqlite が必要です。',
	'author'				=> array(
		'name' 		=> 'ocean',
		'www'			=> 'http://www.bbmods.info/bbs/',),
	'licence' 				=> 'http://opensource.org/licenses/gpl-license.php GNU General Public License v2',
);
$adm['menu_order'] = array(
	'Config' 			=> 0,
	'CountryList' 	=> 1,
	'IPtoCountry' 	=> 2,
	'UserList' 		=> 3,
	'ImportIPRecord' 	=> 4,
);
$config_default = array(
	'enabled' 					=> 1,
	'checkRegistrationIP'	=> 1,
	'countryListType' 		=> 0,
	'countryList'				=> '',
 );