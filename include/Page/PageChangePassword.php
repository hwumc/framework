<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageChangePassword extends PageBase
{

	public function __construct()
	{
		$this->mIsProtectedPage = true;
	}

	protected function runPage()
	{
		$this->mSmarty->assign("content","Not yet available.".$pinfo);
	}
}
