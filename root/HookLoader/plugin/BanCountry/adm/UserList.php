<?php

class phpBB3_HookLoaderPluginAdm_BanCountry_UserList
{
	const USERS_PER_PAGE = 20;
	const DEFAULT_SORT_DIR = 'd';
	const DEFAULT_SORT_KEY = 'user_lastvisit';
	const countryListType_BANNED 	= '0';
	const countryListType_ALLOWED 	= '1';
	
	public $API;
	
	private $IPtoCountry;
	private $BanCountryDB;
	private $template;
	
	private $start;
	private $sort_key;
	private $sort_dir;
	private $sort_key_sql;
	private $total_users;
	
	function __construct()
	{
		global $cache;
		
		include_once PHPBB_HOOKLOADER_BANCOUNTRY_ROOT_PATH . 'include/IPtoCountry/IPtoCountry.php';
		try {
			$this->IPtoCountry = new IPtoCountry(PHPBB_HOOKLOADER_BANCOUNTRY_ROOT_PATH . 'include/IPtoCountry/IPtoCountry.db');
		}
		catch (IPtoCountryException $e) {
			$e->getException();
		}
		
		$default_mode = ($cache->get('_HookLoader_BanCountry_UserList'))? 'run' : 'intro';
		
		$this->mode			= request_var('mode', $default_mode);
		$this->start 			= request_var('start', 0);
		$this->sort_key 		= request_var('sk', self::DEFAULT_SORT_KEY);
		$this->sort_dir 		= request_var('sd', self::DEFAULT_SORT_DIR);
		$this->sort_key_sql 	= array(
			'username_clean' 	=> 'u.username_clean',
			'user_rank' 		=> 'u.user_rank',
			'user_ip' 			=> 'u.user_ip',
			'user_posts' 		=> 'u.user_posts',
			'user_regdate' 	=> 'u.user_regdate', 
			'user_lastvisit' 	=> 'u.user_lastvisit',
		);
		if (!isset($this->sort_key_sql[$this->sort_key])) {
			$this->sort_key = self::DEFAULT_SORT_KEY;
		}

		global $phpbb_root_path, $phpEx;
		include_once($phpbb_root_path . 'includes/functions_display.php');
	}
	
	function main()
	{
		$Panel = $this->API->Template()->Custom();
		$Panel->create_head($this->API->Plugin->lang['USERLIST_TITLE'], $this->API->Plugin->lang['USERLIST_TITLE_EXPLAIN']);
		$Panel->set_template('UserList.html');
		$this->template = $Panel->get_template();
		
		$this->template->assign_vars(array(
			'S_MODE' => $this->mode,
		));
		switch ($this->mode)
		{
			case 'intro' :
				$this->intro();
				break;
			case 'run' :
				try {
					$this->run();
				}
				catch (IPtoCountryException $e) {
					$e->getException();
				}
				break;
			default :
				return;
		}
		
		$Panel->create_body();
	}
	
	private function intro()
	{
	}
	
	private function run()
	{
		$this->BanCountryDB = new phpBB3_BanCountryDB($this->API->Plugin);
		
		global $db, $user, $cache;
		global $phpbb_admin_root, $phpbb_root_path, $phpEx;

		$userlist = $this->draw_userlist();

		// Build a relevant pagination_url
		$params = $sort_params = $post_params = $first_char_params = $countryListType_params = array();
		$this->set_param($params, $sort_params, $post_params, $first_char_params, $countryListType_params);
		$pagination_url 		= append_sid($this->API->Page()->root_url(), implode('&amp;', $params));
		$sort_url 				= append_sid($this->API->Page()->root_url(), implode('&amp;', $sort_params));
		$u_action 				= append_sid($this->API->Page()->root_url(), implode('&amp;', $post_params));
		$first_char_url 		= append_sid($this->API->Page()->root_url(), implode('&amp;', $first_char_params));
		$countryListType_url	= append_sid($this->API->Page()->root_url(), implode('&amp;', $countryListType_params));

		foreach ($userlist as $row)
		{
			$rank_title = $rank_img = '';
			get_user_rank($row['user_rank'], (($row['user_id'] == ANONYMOUS) ? false : $row['user_posts']), $rank_title, $rank_img, $rank_img_src);

			$last_visit = (!empty($row['session_time'])) ? $row['session_time'] : $row['user_lastvisit'];

			$this->template->assign_block_vars('users', array(
				'NAME'			=> $row['username'],
				'COUNTRY'		=> $row['user_country'],

				'USER_ID'		=> $row['user_id'],
				'RANK_TITLE'	=> $rank_title,
				'JOINED'		=> $user->format_date($row['user_regdate']),
				'VISITED'		=> (empty($last_visit)) ? ' - ' : $user->format_date($last_visit),
				'POSTS'		=> ($row['user_posts']) ? $row['user_posts'] : 0,

				'RANK_IMG'			=> $rank_img,
				'RANK_IMG_SRC'	=> $rank_img_src,

				'USERNAME_FULL'	=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'USERNAME'			=> get_username_string('username', $row['user_id'], $row['username'], $row['user_colour']),
				'USER_COLOR'		=> get_username_string('colour', $row['user_id'], $row['username'], $row['user_colour']),
				'U_VIEW_PROFILE'	=> get_username_string('profile', $row['user_id'], $row['username'], $row['user_colour']),

				'IP'				=> $row['user_ip'],

				'U_USER_COUNTRY'	=> $sort_url . '&amp;user_country=' . $row['user_country'],
				'U_USER_IP'		=> append_sid($phpbb_admin_root . 'index.php?', array('i'=>'users', 'mode'=>'overview', 'action'=>'whois', 'user_ip'=>$row['user_ip'])),
			));

		}

		unset($search_params, $sort_params);
			
		// Generate page
		$this->template->assign_vars(array(
			'PAGINATION'	=> generate_pagination($pagination_url, $this->total_users, self::USERS_PER_PAGE, $this->start),
			'PAGE_NUMBER'	=> on_page($this->total_users, self::USERS_PER_PAGE, $this->start),
			'TOTAL_USERS'	=> sprintf($this->API->Plugin->lang['USERLIST_LIST_USERS'], $this->total_users),

			'U_SORT_USERNAME'		=> $sort_url . '&amp;sk=username_clean&amp;sd=' . (($this->sort_key == 'username_clean' && $this->sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_RANK'			=> $sort_url . '&amp;sk=user_rank&amp;sd=' . (($this->sort_key == 'user_rank' && $this->sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_IP'			=> $sort_url . '&amp;sk=user_ip&amp;sd=' . (($this->sort_key == 'user_ip' && $this->sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_POSTS'		=> $sort_url . '&amp;sk=user_posts&amp;sd=' . (($this->sort_key == 'user_posts' && $this->sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_JOINED'		=> $sort_url . '&amp;sk=user_regdate&amp;sd=' . (($this->sort_key == 'user_regdate' && $this->sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_VISITED'		=> $sort_url . '&amp;sk=user_lastvisit&amp;sd=' . (($this->sort_key == 'user_lastvisit' && $this->sort_dir == 'a') ? 'd' : 'a'),

			'U_ACTION'					=> $u_action,
			'FIRST_CHAR_URL'			=> $first_char_url,
			'COUNTRYLISTTYPE_URL'	=> $countryListType_url,
		));
	}
	
	private function draw_userlist()
	{
		global $db, $cache;

		$first_char 			= request_var('first_char', '');
		$countryListType 		= request_var('countryListType', '');
		$user_country 		= utf8_normalize_nfc(request_var('user_country', '', TRUE));
		$otherthan_country 	= request_var('otherthan_country', '');

		if (!preg_match('/^\w+$/iu', $user_country)) {
			$user_country = '';
		}
		if (!in_array($user_country, array('localnetwork', 'unknown'))) {
			$user_country = strtoupper($user_country);
		}

		$this->template->assign_vars(array(
			'FIRST_CHAR'				=> $first_char,
			'USER_COUNTRY'			=> $user_country,
			'COUNTRYLISTTYPE'			=> $countryListType,
			'S_OTHERTHAN_COUNTRY'	=> ($otherthan_country)? TRUE : FALSE,
		));

		// Additional sorting options for user search ... if search is enabled, if not
		// then only admins can make use of this (for ACP functionality)
		$sql_select = $sql_where_data = $sql_from = $sql_where = $order_by = '';

		if ($first_char == 'other') {
			for ($i = 97; $i < 123; $i++)
			{
				$sql_where .= ' AND u.username_clean NOT ' . $db->sql_like_expression(chr($i) . $db->any_char);
			}
		} else if ($first_char) {
			$sql_where .= ' AND u.username_clean ' . $db->sql_like_expression(substr($first_char, 0, 1) . $db->any_char);
		}

		// Sorting and order
		$order_by .= $this->sort_key_sql[$this->sort_key] . ' ' . (($this->sort_dir == 'a') ? 'ASC' : 'DESC');

		// Get us some users :D
		$sql = "SELECT *
			FROM " . USERS_TABLE . " u
			WHERE u.user_type IN (" . USER_NORMAL . ', ' . USER_FOUNDER . ")
				$sql_where
				ORDER BY $order_by";
		$result = $db->sql_query($sql);
		$userlist = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$countryList = $this->BanCountryDB->countryList();
		
		$cached_data = $cache->get('_HookLoader_BanCountry_UserList');
		if ($cached_data) {
			$userlist = $cached_data['userlist'];
		} else {
			foreach ($userlist as $i => $row) {
				$userlist[$i]['user_country'] = $this->IPtoCountry->toCountry($row['user_ip']);
			}
			$cache->put('_HookLoader_BanCountry_UserList', array(
				'userlist' => $userlist,
			), 86400);
		}
		
		$new_userlist = array();
		foreach ($userlist as $i => $row)
		{
			if ($user_country) {
				if (!$otherthan_country) {
					if ($row['user_country'] != $user_country) {
						continue;
					}
				} else {
					if ($row['user_country'] == $user_country) {
						continue;
					}
				}
			}
			if ($countryListType !== '') {
				if ($countryListType === self::countryListType_BANNED) {
					if ($this->API->Plugin->config['countryListType'] && in_array($row['user_country'], $countryList)
					|| !$this->API->Plugin->config['countryListType'] && !in_array($row['user_country'], $countryList))
					{
						continue;
					}
				} elseif ($countryListType === self::countryListType_ALLOWED) {
					if ($this->API->Plugin->config['countryListType'] && !in_array($row['user_country'], $countryList)
					|| !$this->API->Plugin->config['countryListType'] && in_array($row['user_country'], $countryList))
					{
						continue;
					}
				}
			}
			$new_userlist[] = $row;
		}
		$this->total_users = count($new_userlist);
		return array_slice($new_userlist, $this->start, self::USERS_PER_PAGE);
	}		
	
	private function set_param(&$params, &$sort_params, &$post_params, &$first_char_params, &$countryListType_params)
	{
		$check_params = array(
			'mode'					=> array('mode', 'intro'),
			'sk'					=> array('sk', self::DEFAULT_SORT_KEY),
			'sd'					=> array('sd', self::DEFAULT_SORT_DIR),
			'first_char'			=> array('first_char', ''),
			'countryListType'		=> array('countryListType', ''),
			'user_country'		=> array('user_country', ''),
			'otherthan_country'	=> array('otherthan_country', ''),
		);
		foreach ($check_params as $key => $call)
		{
			if (!isset($_REQUEST[$key])) {
				continue;
			}

			$param = call_user_func_array('request_var', $call);
			$param = urlencode($key) . '=' . ((is_string($param)) ? urlencode($param) : $param);
			$params[] = $param;

			if ($key != 'sk' && $key != 'sd') {
				$sort_params[] = $param;
			}
			if ($key != 'user_country' && $key != 'otherthan_country') {
				$post_params[] = $param;
			}
			if ($key != 'first_char') {
				$first_char_params[] = $param;
			}
			if ($key != 'countryListType') {
				$countryListType_params[] = $param;
			}
		}
	}
}