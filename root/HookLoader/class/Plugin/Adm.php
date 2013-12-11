<?php
class phpBB3_HookLoaderPlugin_Adm extends phpBB3_HookLoaderPlugin_AdmBase {
	private $flag_assign_template_vars = FALSE;
	function main() {
		$this->assign_template_vars ();
	}
	private function assign_template_vars() {
		if ($this->flag_assign_template_vars == FALSE) {
			global $template;
			$template->assign_vars ( array (
					'HOOKLOADER_PLUGIN_TITLE' => $this->plugin_name,
					'HOOKLOADER_PLUGIN_VERSION' => $this->Info->plugin ( 'version' ) 
			) );
			$this->flag_assign_template_vars = TRUE;
		}
	}
	function haveIntro() {
		if (is_file ( PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$this->plugin_name}/adm/intro.php" )) {
			return TRUE;
		}
		return FALSE;
	}
}
class phpBB3_HookLoaderPlugin_AdmBase extends phpBB3_HookLoaderPlugin_Base {
	function set_config($config_name, $config_value, $is_dynamic = FALSE) {
		global $config, $db;
		
		$prefix = 'HookLoader_' . $this->plugin_name . '_';
		if ($config_value === NULL) {
			unset ( $this->config [$config_name] );
			
			$sql = 'DELETE FROM ' . CONFIG_TABLE . " WHERE config_name = '" . $db->sql_escape ( $prefix . $config_name ) . "'";
			$db->sql_query ( $sql );
		} else {
			$this->config [$config_name] = $config_value;
			
			set_config ( $prefix . $config_name, $config_value, $is_dynamic );
		}
	}
}