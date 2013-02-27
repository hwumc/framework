<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageDbgPageList extends PageBase
{
	public function __construct() {
		$this->mPageUseRight = "diagnostic";
		$this->mMenuGroup = "Diagnostics";
	}

	protected function runPage() {
		$this->mSmarty->assign( "content", "<pre>" . print_r( PageBase::getRegisteredPages(), true ) . "</pre>" );
	}
}
