<?php
class phpBB3_HookLoaderMain_Hook extends ObjectFileSystemFile {
	private $List;
	function main() {
		if (! isset ( $this->List )) {
			$this->List = new phpBB3_HookLoaderMain_HookList ();
		}
	}
	
	/**
	 *
	 * @param string $hooktype        	
	 * @throws phpBB3_HookLoaderException
	 */
	function run($hooktype) {
		$plugin_hook_ary = $this->List->plugin_hookname_ary ( $hooktype );
		if (isset ( $plugin_hook_ary )) {
			foreach ( $plugin_hook_ary as $plugin_name => $hook_names ) {
				foreach ( $hook_names as $hook_name ) {
					$path = PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "{$plugin_name}/hooks/{$hook_name}.php";
					$class_name = 'phpBB3_HookLoaderPluginHook_' . $plugin_name . '_' . $hook_name;
					if (is_file ( $path )) {
						include_once $path;
					} else {
						if (defined ( 'DEBUG' )) {
							throw new phpBB3_HookLoaderException ( "Could not find file '{$path}'." );
						} else {
							continue;
						}
					}
					if (defined ( 'DEBUG' ) && ! class_exists ( $class_name, FALSE )) {
						throw new phpBB3_HookLoaderException ( "Could not find class '{$class_name}' in {$path}." );
					}
					$Hook = new $class_name ();
					if (defined ( 'DEBUG' ) && ! method_exists ( $Hook, $hooktype )) {
						throw new phpBB3_HookLoaderException ( "Could not find method '{$hooktype}' within the class {$class_name} in {$path}." );
					}
					$API = new phpBB3_HookLoaderAPI ( $plugin_name );
					$Hook->API = $API->Hook ( $hook_name );
					$Hook->{$hooktype} ();
				}
			}
		}
	}
}
class phpBB3_HookLoaderMain_HookList {
	private $plugin_hookname_ary;
	function __construct() {
		$this->set_data ();
	}
	function plugin_hookname_ary($hookType) {
		if (isset ( $this->plugin_hookname_ary [$hookType] )) {
			return $this->plugin_hookname_ary [$hookType];
		} else {
			return NULL;
		}
	}
	private function set_data() {
		global $cache;
		
		if (! defined ( 'DEBUG' )) {
			$cached_data = $cache->get ( '_HookLoaderHook_Main' );
			if ($cached_data) {
				$this->plugin_hookname_ary = $cached_data ['plugin_hookname_ary'];
				return;
			}
		}
		$plugin_hookname_ary = array ();
		foreach ( new DirectoryIterator ( PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH ) as $iterator ) {
			if ($iterator->isDir () && ! $iterator->isDot ()) {
				$plugin_name = $iterator->getFilename ();
				if ($plugin_name == 'HookLoader') {
					continue;
				}
				if (! preg_match ( "/^[a-zA-Z0-9]+$/", $plugin_name )) {
					if (defined ( 'DEBUG' )) {
						throw new phpBB3_HookLoaderException ( "Plugin's name must be alphanumeric characters." );
					} else {
						continue;
					}
				}
				
				$Plugin = new phpBB3_HookLoaderPlugin ( $plugin_name );
				if (! $Plugin->isSetup ()) {
					continue;
				}
				
				$hook_root_path = PHPBB_HOOKLOADER_PLUGIN_ROOT_PATH . "$plugin_name/hooks/";
				if (! file_exists ( $hook_root_path )) {
					continue;
				}
				foreach ( new DirectoryIterator ( $hook_root_path ) as $iterator2 ) {
					if ($iterator2->isFile ()) {
						$hook_name = basename ( $iterator2->getFilename (), '.php' );
						
						include_once ($hook_root_path . "{$hook_name}.php");
						
						$class_name = 'phpBB3_HookLoaderPluginHook_' . $plugin_name . '_' . $hook_name;
						if (! class_exists ( $class_name )) {
							if (defined ( 'DEBUG' )) {
								throw new phpBB3_HookLoaderException ( "Could not find class '$class_name' in '{$hook_root_path}{$hook_name}.php'." );
							} else {
								continue;
							}
						}
						foreach ( phpBB3_HookLoaderConst::hookTypes () as $hookType ) {
							if (method_exists ( $class_name, $hookType )) {
								$plugin_hookname_ary [$hookType] [$plugin_name] [] = $hook_name;
							}
						}
					}
				}
			}
		}
		$this->plugin_hookname_ary = $plugin_hookname_ary;
		if (! defined ( 'DEBUG' )) {
			$cache->put ( '_HookLoaderHook_Main', array (
					'plugin_hookname_ary' => $plugin_hookname_ary 
			) );
		}
	}
}