<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageDbgRightList extends PageBase
{
	public function __construct() {
		$this->mPageUseRight = "diagnostic";
		$this->mMenuGroup = "Diagnostics";
	}

	protected function runPage() {
		$this->mSmarty->assign( "content", "<pre>" . print_r( User::getById( Session::getLoggedInUser())->getRights(), true ) . "</pre>" .
            "<h2>Session rights</h2><p>Rights assigned to this session only</p>" . 
            "<pre>" . print_r( Session::getSessionRights(), true ) . "</pre>"
            );
	}
}
