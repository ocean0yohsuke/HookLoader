<?php
class phpBB3_HookLoaderAPIHook_Page extends phpBB3_HookLoaderAPIHook_Base {
	private $inBoard = FALSE;
	private $inACP = FALSE;
	private $inUCP = FALSE;
	private $inMCP = FALSE;
	private $name = '';
	function construct() {
		global $phpbb_root_path;
		
		$this->name = basename ( $_SERVER ['SCRIPT_NAME'] );
		
		if ($phpbb_root_path === './') {
			switch ($this->name) {
				case 'ucp.php' :
					$this->inUCP = TRUE;
					break;
				case 'mcp.php' :
					$this->inMCP = TRUE;
					break;
				default :
					$this->inBoard = TRUE;
			}
		} elseif (defined ( 'ADMIN_START' )) {
			$this->inACP = TRUE;
		}
	}
	function main() {
	}
	function inBoard() {
		return $this->inBoard;
	}
	function inACP() {
		return $this->inACP;
	}
	function inUCP() {
		return $this->inUCP;
	}
	function inMCP() {
		return $this->inMCP;
	}
	function name() {
		return $this->name;
	}
}