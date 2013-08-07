<?php

class phpBB3_HookLoaderPluginAdm_BanCountry_IPtoCountry
{
	const RECORDS_PER_PAGE = 20;

	private $IPtoCountry;
	private $template;

	private $start;
	private $ip;
	private $params = array();
	
	function __construct()
	{
		include_once PHPBB_HOOKLOADER_BANCOUNTRY_ROOT_PATH . 'include/IPtoCountry/IPtoCountry.php';
		
		$this->start 	= request_var('start', 0);
		$this->ip 		= request_var('ip', '');
		$this->set_params();
	}
	private function set_params()
	{
		$check_params = array(
			//'start'	=> array('start', 0),
			'ip'		=> array('ip', ''),
		);
		foreach ($check_params as $key => $call)
		{
			if (!isset($_REQUEST[$key])) {
				continue;
			}
			$param = call_user_func_array('request_var', $call);
			$param = urlencode($key) . '=' . ((is_string($param)) ? urlencode($param) : $param);
			$this->params[] = $param;
		}
	}

	function main()
	{
		$Panel = $this->API->Template()->Custom();
		$Panel->set_template('IPtoCountry.html');
		$Panel->create_head($this->API->Plugin->lang['IPTOCOUNTRY_TITLE'], $this->API->Plugin->lang['IPTOCOUNTRY_TITLE_EXPLAIN']);
		$this->template = $Panel->get_template();
		try {
			$this->body();
		} catch (IPtoCountryException $e){
			$this->template->assign_vars(array(
				'S_ERROR'			=> TRUE,
				'ERROR_MSG'		=> $e->getMessage(),
			));
		}
		$Panel->create_body();
	}
	private function body()
	{
		$this->IPtoCountry = new IPtoCountry(PHPBB_HOOKLOADER_BANCOUNTRY_ROOT_PATH . 'include/IPtoCountry/IPtoCountry.db');
		
		$this->template->assign_vars(array(
			'U_ACTION'	=> append_sid($this->API->Page()->root_url(), "start={$this->start}"),
		));
		
		$IPRecords = $this->IPtoCountry->IPRecords($this->start, self::RECORDS_PER_PAGE);
		foreach ($IPRecords as $record) 
		{
			$this->template->assign_block_vars('iprecords', array(
				'REGISTRY'		=> $record['registry'],
				'CC'			=> $record['cc'],
				'TYPE'			=> $record['type'],
				'START'		=> $record['start'],
				'VALUE'		=> $record['value'],
				'DATE'			=> $record['date'],
			));
		}
		$total_records = $this->IPtoCountry->rows_total();
		$pagination_url = append_sid($this->API->Page()->root_url(), implode('&amp;', $this->params));
		$this->template->assign_vars(array(
			'PAGINATION'		=> generate_pagination($pagination_url, $total_records, self::RECORDS_PER_PAGE, $this->start),
			'PAGE_NUMBER'		=> on_page($total_records, self::RECORDS_PER_PAGE, $this->start),
			'TOTAL_RECORDS'	=> $total_records,
		));
		
		if (!empty($this->ip)) {
			$this->IPtoCountry();
		}
	}
	
	private function IPtoCountry()
	{
		$country = $this->IPtoCountry->toCountry($this->ip);
		$this->template->assign_vars(array(
			'IPADDRESS'		=> $this->ip,
			'RESULT_COUNTRY'	=> $country,
		));
	}
}