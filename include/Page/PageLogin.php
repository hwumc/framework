<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageLogin extends PageBase
{

	public function __construct()
	{
		$this->mIsSpecialPage = true;
	}
	
	protected function runPage()
	{
		$this->mBasePage = "login.tpl";
	
		if(WebRequest::wasPosted()) // sanity check
		{
			if(! ($username = WebRequest::postString("lgUser")))
			{
				// no username specified
				$this->redirect("nouser", null);
				return;
			}
			if(! ($password = WebRequest::postString("lgPasswd")))
			{
				// no password specified
				$this->redirect("nopass", null);
				return;
			}

			$cust = User::getByName($username);
			if($cust == null)
			{
				// customer doesn't exist. offer to signup or retry?
				$this->redirect("invalid", null);
				return;
			}

			if(! $cust->isMailConfirmed())
			{
				// customer hasn't confirmed their email
				$this->redirect("noconfirm", $cust);
				return;
			}
			
			if(! $cust->authenticate($password) )
			{
				// not a valid password
				$this->redirect("invalid", $cust);
				return;
			}

			// seems to be ok.

			// set up the session
			Session::setLoggedInUser($cust->getId());
			
			// redirect back to the main page.
			$this->redirect(null, $cust);

		}
		else
		{
		}
	}

	private function redirect($error, User $user)
	{
        global $cWebPath;
		
		// redirect back to the main page.
		$this->mHeaders[] = "HTTP/1.1 303 See Other";
        $this->mIsRedirecting = true;
        
		if($error) {
            Session::appendError( "login-" . $error );
            $this->mHeaders[] = "Location: " . $cWebPath . "/index.php/Login";
        } else {
            if($user->getProfileReview() == 1)
            {
                $user->setProfileReview(0);
                $user->save();
                $this->mHeaders[] = "Location: " . $cWebPath . "/index.php/EditProfile?review=yes";
            } 
            else if($user->getPasswordReset() == 1)
            {
                $user->setProfileReview(0);
                $user->save();
                $this->mHeaders[] = "Location: " . $cWebPath . "/index.php/ChangePassword?forced=yes";
            } 
            else
            {
                $this->mHeaders[] = "Location: " . $cWebPath . "/index.php" . WebRequest::get("returnto");
            }    
        }
		return;
	}

	// this is a hook function
	// a global hook
	// don't break it.
	// (yeah, that means it's called on EVERY page load)
	public static function getErrorDisplay($arguments)
	{
		$smarty = $arguments[1];
	
		$error = WebRequest::get("lgerror") or "" ;

		$smarty->assign("lgerror", $error);
		
		return true;
	}
	
	public static function loginModuleOverride($arguments)
	{
		$smarty = $arguments[1];

		$smarty->assign("loginoverride", "");
		
		if(Session::isLoggedIn())
		{
			$customer = User::getById(Session::getLoggedInUser());
			$smarty->assign("loginoverride", "userpanel");
			$smarty->assign("userRealName", $customer->getFullName());
		}
	}
}
