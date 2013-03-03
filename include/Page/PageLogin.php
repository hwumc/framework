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
				// no email address specified
				$this->redirect("noemail");
				return;
			}
			if(! ($password = WebRequest::postString("lgPasswd")))
			{
				// no password specified
				$this->redirect("nopass");
				return;
			}

			$cust = User::getByName($username);
			if($cust == null)
			{
				// customer doesn't exist. offer to signup or retry?
				$this->redirect("invalid");
				return;
			}

			if(! $cust->isMailConfirmed())
			{
				// customer hasn't confirmed their email
				$this->redirect("noconfirm");
				return;
			}
			
			if(! $cust->authenticate($password) )
			{
				// not a valid password
				$this->redirect("invalid");
				return;
			}

			// seems to be ok.

			// set up the session
			Session::setLoggedInUser($cust->getId());
			
			// redirect back to the main page.
			$this->redirect();

		}
		else
		{
		}
	}

	private function redirect($error = null)
	{
		$append = "";
		if($error)
		{
			$append = "?lgerror=" . $error;
		}
		
		global $cWebPath;
		
		// redirect back to the main page.
		$this->mHeaders[] = "HTTP/1.1 303 See Other";
		$this->mHeaders[] = "Location: " . $cWebPath . "/index.php" . WebRequest::get("returnto") . $append;
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
