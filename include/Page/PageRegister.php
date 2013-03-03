<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageRegister extends PageBase
{
	public function __construct()
	{
		$this->mPageUseRight = "public";
		$this->mIsSpecialPage = true;
	}

	protected function runPage()
	{
		$this->mBasePage = "register/register.tpl";
	}
}
