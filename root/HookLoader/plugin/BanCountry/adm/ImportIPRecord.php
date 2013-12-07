<?php
class phpBB3_HookLoaderPluginAdm_BanCountry_ImportIPRecord {
	function main() {
		$Panel = $this->API->Template ()->Custom ();
		$Panel->set_template ( 'ImportIPRecord.html' );
		$Panel->create_head ( $this->API->Plugin->lang ['IMPORTIPRECORD_TITLE'] );
		$Panel->create_body ();
	}
}
