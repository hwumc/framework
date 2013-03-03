<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageEditProfile extends PageBase
{

	public function __construct()
	{
		$this->mPageUseRight = "user";
		$this->mIsSpecialPage = true;
	}

	protected function runPage()
	{
		$this->mBasePage = "profile/edit.tpl";
		$this->mSmarty->assign( "email", "" );
		$this->mSmarty->assign( "realname", "" );
		$this->mSmarty->assign( "mobile", "" );
		$this->mSmarty->assign( "experience", "" );
		$this->mSmarty->assign( "medicalcheck", "checked=\"true\"" );
		$this->mSmarty->assign( "medical", "" );
		$this->mSmarty->assign( "contactname", "" );
		$this->mSmarty->assign( "contactphone", "" );
		
	}
}
