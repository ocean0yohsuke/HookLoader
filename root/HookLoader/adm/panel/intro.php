<?php

class phpBB3_HookLoaderAdmMain_HLP_PanelPage_intro extends phpBB3_HookLoaderAdmMain_HLP_PanelPage_base
{
	function main()
	{
		$this->__invoke('.')->tool('intro');
	}
}