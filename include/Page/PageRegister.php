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
		if( WebRequest::wasPosted() ) {
			$u = new User();
			
			if( WebRequest::post( "username" ) == "" || WebRequest::post( "username" ) === false )  throw new MissingFieldException();
			$u->setUsername( WebRequest::post( "username" ) );
			
			if( WebRequest::post( "password" ) == "" || WebRequest::post( "password" ) === false )  throw new MissingFieldException();
			if( WebRequest::post( "confirmpassword" ) != WebRequest::post( "password" ) )  throw new ArgumentException();
			$u->setPassword( WebRequest::post( "password" ) );
			
			if( WebRequest::post( "email" ) == "" || WebRequest::post( "email" ) === false )  throw new MissingFieldException();			
			$u->setEmail( WebRequest::post( "email" ) );
			
			if( WebRequest::post( "realname" ) == "" || WebRequest::post( "realname" ) === false )  throw new MissingFieldException();
			$u->setFullName( WebRequest::post( "realname" ) );
			
			if( WebRequest::post( "mobile" ) == "" || WebRequest::post( "mobile" ) === false )  throw new MissingFieldException();
			$u->setMobile( WebRequest::post( "mobile" ) );
		
			if( WebRequest::post( "contactname" ) == "" || WebRequest::post( "contactname" ) === false )  throw new MissingFieldException();
			$u->setEmergencyContact( WebRequest::post( "contactname" ) );
            
            if( WebRequest::post( "contactphone" ) == "" || WebRequest::post( "contactphone" ) === false )  throw new MissingFieldException();
			$u->setEmergencyContactPhone( WebRequest::post( "contactphone" ) );    
            
            if( WebRequest::post( "experience" ) == "" || WebRequest::post( "experience" ) === false )  throw new MissingFieldException();
			$u->setExperience( WebRequest::post( "experience" ) );
            
            if( WebRequest::post( "medical" ) === false )  throw new MissingFieldException();
			$u->setMedical( WebRequest::post( "medical" ) );
		
			$u->save();
		
			$this->mBasePage = "register/registered.tpl";
		
		} else {
			return;
		}
	}
}
