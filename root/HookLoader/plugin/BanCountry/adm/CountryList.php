<?php
class phpBB3_HookLoaderPluginAdm_BanCountry_CountryList {
	public $API;
	private $BanCountry;
	private $template;
	function __construct() {
	}
	function main() {
		$this->BanCountry = new phpBB3_BanCountryMain ( $this->API->Plugin );
		
		$Panel = $this->API->Template ()->Custom ();
		$Panel->create_head ( $this->API->Plugin->lang ['COUNTRYLIST_TITLE'], $this->API->Plugin->lang ['COUNTRYLIST_TITLE_EXPLAIN'] );
		$Panel->set_template ( 'CountryList.html' );
		$this->template = $Panel->get_template ();
		$this->body ();
		$Panel->create_body ();
	}
	function body() {
		$submit_listType = request_var ( 'submit_listType', FALSE );
		$submit_add = request_var ( 'submit_add', FALSE );
		$submit_del = request_var ( 'submit_del', FALSE );
		
		$error = array ();
		$notice = array ();
		
		if ($submit_listType) {
			$this->submit_listType ( $error, $notice );
		} elseif ($submit_add) {
			$this->submit_add ( $error, $notice );
		} elseif ($submit_del) {
			$this->submit_del ( $error, $notice );
		}
		
		$this->template->assign_vars ( array (
				'LISTTYPE_VALUE' => $this->API->Plugin->config ['countryListType'] 
		) );
		
		if ($this->BanCountry->isBannedYourself ()) {
			$error [] = $this->API->Plugin->lang ['BANNED_YOURSELF'];
		}
		
		$this->template->assign_vars ( array (
				'S_ERROR' => (sizeof ( $error )) ? TRUE : FALSE,
				'ERROR_MSG' => implode ( '<br />', $error ) 
		) );
		$this->template->assign_vars ( array (
				'S_NOTICE' => (sizeof ( $notice )) ? TRUE : FALSE,
				'NOTICE_MSG' => implode ( '<br />', $notice ) 
		) );
		
		$countryList = $this->BanCountry->DB ()->countryList ();
		foreach ( $countryList as $country_name ) {
			$this->template->assign_block_vars ( 'country', array (
					'NAME' => $country_name 
			) );
		}
	}
	function submit_listType(&$error, &$notice) {
		$listType = request_var ( 'listType', 0 );
		
		$this->API->Plugin->set_config ( 'countryListType', $listType );
		
		if ($this->BanCountry->isBannedYourself ()) {
			$this->API->Plugin->set_config ( 'enabled', 0 );
			$error [] = $this->API->Plugin->lang ['COUNTRYLIST_DISABLED_THIS'];
		}
		$notice [] = $this->API->Plugin->lang ['COUNTRYLIST_LISTTYPE_SUCCESSED'];
	}
	function submit_add(&$error, &$notice) {
		$added_countryList = utf8_normalize_nfc ( request_var ( 'countryList', '', TRUE ) );
		
		if (! $added_countryList) {
			$error [] = $this->API->Plugin->lang ['NO_COUNTRIES'];
			return;
		}
		
		$countryList = array ();
		$added_countryList = array_unique ( explode ( "\n", $added_countryList ) );
		foreach ( $added_countryList as $country ) {
			$country = trim ( $country );
			if (in_array ( $country, array (
					'localnetwork',
					'unknown' 
			) )) {
				$countryList [] = $country;
				continue;
			}
			$country = strtoupper ( $country );
			if (! preg_match ( '/^\w\w$/iu', $country )) {
				$error [] = sprintf ( $this->API->Plugin->lang ['COUNTRYNAME_IS_INVALID'], $country );
				return;
			}
			$countryList [] = $country;
		}
		$added_countryList = $countryList;
		
		$current_countryList = $this->BanCountry->DB ()->countryList ();
		$new_countryList = array_unique ( array_merge ( $current_countryList, $added_countryList ) );
		asort ( $new_countryList );
		
		$this->BanCountry->DB ()->set_countryList ( $new_countryList );
		
		if ($this->BanCountry->isBannedYourself ()) {
			$this->API->Plugin->set_config ( 'enabled', 0 );
			$error [] = $this->API->Plugin->lang ['COUNTRYLIST_DISABLED_THIS'];
		}
		$notice [] = $this->API->Plugin->lang ['COUNTRYLIST_ADD_SUCCESSED'];
	}
	function submit_del(&$error, &$notice) {
		$deleted_countryList = request_var ( 'countryList', array (
				0 => '' 
		) );
		
		if (! count ( $deleted_countryList )) {
			$error [] = $this->API->Plugin->lang ['NO_COUNTRIES'];
			return;
		}
		
		$current_countryList = $this->BanCountry->DB ()->countryList ();
		$new_countryList = array_diff ( $current_countryList, $deleted_countryList );
		$this->BanCountry->DB ()->set_countryList ( $new_countryList );
		
		if ($this->BanCountry->isBannedYourself ()) {
			$this->API->Plugin->set_config ( 'enabled', 0 );
			$error [] = $this->API->Plugin->lang ['COUNTRYLIST_DISABLED_THIS'];
		}
		$notice [] = $this->API->Plugin->lang ['COUNTRYLIST_DEL_SUCCESSED'];
	}
}

