<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageMessageEditor extends PageBase
{
	public function __construct()
	{
		$this->mPageUseRight = "messages-view";
		$this->mMenuGroup = "SystemInfo";
		$this->mPageRegisteredRights = array( "messages-edit", "messages-clear" );
	}

	protected function runPage()
	{

	}
}
