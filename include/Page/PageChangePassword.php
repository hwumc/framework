<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageChangePassword extends PageBase
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
			
			if( $user->authenticate( WebRequest::post( "old" ) ) ) {
				if( WebRequest::post( "new" ) == "" || WebRequest::post( "new" ) === false )  throw new MissingFieldException();
				if( WebRequest::post( "confirm" ) != WebRequest::post( "new" ) )  throw new ArgumentException();

				$user->setPassword( WebRequest::post( "new" ) );
				
				$user->save();
			}
			
			
			
			global $cScriptPath;
			$this->mHeaders[] = "HTTP/1.1 303 See Other";
			$this->mHeaders[] = "Location: " . $cScriptPath . "/EditProfile";
		
		} else {
			$this->mBasePage = "profile/chpw.tpl";
		}

	}
}
