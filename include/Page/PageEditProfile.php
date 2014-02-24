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
            
            if( trim(WebRequest::post( "experience" )) == "" ) 
            {
                $this->triggerError("no-experience");
                return;
            }
			
			$user->setEmail( WebRequest::post( "email" ) );
			$user->setFullName( WebRequest::post( "realname" ) );
			$user->setMobile( WebRequest::post( "mobile" ) );
			$user->setEmergencyContact( WebRequest::post( "contactname" ) );
			$user->setEmergencyContactPhone( WebRequest::post( "contactphone" ) );
			$user->setExperience( WebRequest::post( "experience" ) );
			$user->setMedical( WebRequest::post( "medical" ) );
            
            $driver = WebRequest::post( "isdriver" );
            $user->setIsDriver( $driver == 'on' ? 1 : 0 );
			
			$user->save();
			
			
			global $cScriptPath;
			$this->mHeaders[] = "HTTP/1.1 303 See Other";
			$this->mHeaders[] = "Location: " . $cScriptPath . "/EditProfile";
		
		} else {
			$this->mBasePage = "profile/edit.tpl";
			$this->mSmarty->assign( "email", $user->getEmail() );
			$this->mSmarty->assign( "realname", $user->getFullName() );
			$this->mSmarty->assign( "mobile", $user->getMobile() );
			$this->mSmarty->assign( "experience", $user->getExperience() );
			$this->mSmarty->assign( "medicalcheck", ($user->getMedical() == "" ? "" : 'checked="true"') );
			$this->mSmarty->assign( "isdriver", ($user->isDriver() == 1 ? 'checked="true"' : '') );
			$this->mSmarty->assign( "medical", $user->getMedical() );
			$this->mSmarty->assign( "contactname", $user->getEmergencyContact() );
			$this->mSmarty->assign( "contactphone", $user->getEmergencyContactPhone() );
            
            $this->mSmarty->assign( "gravatar", $user->getGravatarHash() );

			$this->mSmarty->assign( "review", WebRequest::get('review'));
		}
	}
    
    private function triggerError( $errorCode )
    {        
        
        $this->mSmarty->assign( "email", WebRequest::post( "email" ) );
        $this->mSmarty->assign( "realname", WebRequest::post( "realname" ) );
        $this->mSmarty->assign( "mobile", WebRequest::post( "mobile" ) );
        $this->mSmarty->assign( "experience", WebRequest::post( "experience" ) );
        $this->mSmarty->assign( "medicalcheck", (WebRequest::post( "medical" ) == "" ? "" : 'checked="true"') );
        $this->mSmarty->assign( "isdriver", (WebRequest::post( "medical" ) == "on" ? 'checked="true"' : "") );
        $this->mSmarty->assign( "medical", WebRequest::post( "medical" ) );
        $this->mSmarty->assign( "contactname", WebRequest::post( "contactname" ) );
        $this->mSmarty->assign( "contactphone", WebRequest::post( "contactphone" ) );
        
        $this->mSmarty->assign( "gravatar", User::getGravatarHashForEmail(WebRequest::post( "email" )) );
                
        $this->mBasePage = "profile/edit.tpl";
        
        Session::appendError("EditProfile-error-" . $errorCode);
    }
    
    			
}
