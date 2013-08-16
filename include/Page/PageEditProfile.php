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
		$user = User::getById( Session::getLoggedInUser());
	
		if( WebRequest::wasPosted() ) {
			$this->mBasePage = "blank.tpl";
			
			$user->setEmail( WebRequest::post( "email" ) );
			$user->setFullName( WebRequest::post( "realname" ) );
			
			$user->save();
			
			
			global $cScriptPath;
			$this->mHeaders[] = "HTTP/1.1 303 See Other";
			$this->mHeaders[] = "Location: " . $cScriptPath . "/EditProfile";
		
		} else {
			$this->mBasePage = "profile/edit.tpl";
			$this->mSmarty->assign( "email", $user->getEmail() );
			$this->mSmarty->assign( "realname", $user->getFullName() );
		}
	}
}
