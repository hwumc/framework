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
		if( WebRequest::wasPosted() )
        {
            $existing = User::getByName( WebRequest::post( "username" ) );
            if( $existing != null )
            {
                $this->triggerError("username-taken");
                return;
            }
                
			if( WebRequest::post( "confirmpassword" ) != WebRequest::post( "password" ) ) 
            {
                $this->triggerError("password-mismatch");
                return;
            }
            
            $u = new User();
			$u->setUsername( WebRequest::post( "username" ) );
			$u->setPassword( WebRequest::post( "password" ) );
			$u->setEmail( WebRequest::post( "email" ) );
			$u->setFullName( WebRequest::post( "realname" ) );
			$u->setMobile( WebRequest::post( "mobile" ) );
			$u->setEmergencyContact( WebRequest::post( "contactname" ) );            
			$u->setEmergencyContactPhone( WebRequest::post( "contactphone" ) );    
            $u->setExperience( WebRequest::post( "experience" ) );
			$u->setMedical( WebRequest::post( "medical" ) );
			$u->save();
		
			$this->mBasePage = "register/registered.tpl";
		
		} else {
            $this->mSmarty->assign( "regusername", "" );
            $this->mSmarty->assign( "regpassword", "" );
            $this->mSmarty->assign( "regemail", "" );
            $this->mSmarty->assign( "regrealname", "" );
            $this->mSmarty->assign( "regmobile", "" );
            $this->mSmarty->assign( "regcontactname", "" );
            $this->mSmarty->assign( "regcontactphone", "" );
            $this->mSmarty->assign( "regexperience", "" );
            $this->mSmarty->assign( "regmedical", "" );
            $this->mSmarty->assign( "regmedicalcheck", "" );
            
			$this->mBasePage = "register/register.tpl";
		}
	}
    
    private function triggerError( $errorCode )
    {        
        $this->mSmarty->assign( "regusername", WebRequest::post( "username" ) );
        $this->mSmarty->assign( "regemail", WebRequest::post( "email" ) );
        $this->mSmarty->assign( "regrealname", WebRequest::post( "realname" ) );
        $this->mSmarty->assign( "regmobile", WebRequest::post( "mobile" ) );
        $this->mSmarty->assign( "regcontactname", WebRequest::post( "contactname" ) );
        $this->mSmarty->assign( "regcontactphone", WebRequest::post( "contactphone" ) );
        $this->mSmarty->assign( "regexperience", WebRequest::post( "experience" ) );
        $this->mSmarty->assign( "regmedical", WebRequest::post( "medical" ) );
        $this->mSmarty->assign( "regmedicalcheck", WebRequest::post( "medical" ) == "" ? "" : 'checked="checked"' );
        
        $this->mBasePage = "register/register.tpl";
        
        Session::appendError("Register-error-" . $errorCode);
    }
}
