<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageMain extends PageBase
{
	public function __construct()
	{
		$this->mIsProtectedPage = false;
	}

	protected function runPage()
	{
		$this->mSmarty->assign("content", "foo bar");
	}
}
