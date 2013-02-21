<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

abstract class PageBase
{
	// is this page a protected by login page?
	// defaults to true, so we protect everything and have to explicitly 
	// unprotect if desired.
	protected $mIsProtectedPage = true;

	// array of extra (per-page) javascript files to add
	protected $mScripts = array();

	// array of extra (per-page) CSS stylesheets to add
	protected $mStyles = array();

	protected $mSmarty;

	// message containing the title of the page
	protected $mPageTitle = "html-title";

	// access right name to access this page
	protected $mAccessName = "public";
	
	// base template to use
	protected $mBasePage = "base.tpl";

	// subnav
	protected $mSubMenu = array();

	
	// main menu
	protected $mMainMenu = array(
		/* Format:
			"Class name" => array(
				"title" => "Message name to display",
				"link" => "Link to show",
				"items" => array(...)
				),
			*/
		"PageMain" => array(
			"title" => "page-home",
			"link" => "/",
			),
		);
		
	// array of HTTP headers to add to the request.
	protected $mHeaders = array();

	protected function setupPage()
	{
		$this->mSmarty = new Smarty();

		$this->mSmarty->registerPlugin(
			"function",
			"message", 
			array(
				"Message", 
				"smartyGetRealMessageContentWithDynamicLanguageFromUser" . 
					"PrefsAndCookies"
				)
			);
		
		$this->addSystemCssJs();
		
		$this->mSmarty->assign("showError", "no");
	}
	
	protected function finalSetup()
	{
		global $gLogger;
		$gLogger->log("MPage final setup");
		if(Session::getLoggedInUser())
		{
			$gLogger->log("uid is set");
			$uid = Session::getLoggedInUser();
			if($uid!=0)
			{
				$gLogger->log("uid is $uid");

				$user = User::getById($uid);

				$gLogger->log("name is" . $user->getUsername());

				$this->mMainMenu["MPageLogout"]["data"] = " (". $user->getFullName().")";
			}
		}
	
		global $cGlobalScripts;
		$scripts = array_merge($cGlobalScripts, $this->mScripts);
		$this->mSmarty->assign("scripts",$scripts);

		global $cGlobalStyles;
		$styles = array_merge($cGlobalStyles, $this->mStyles);
		$this->mSmarty->assign("styles",$styles);

		$this->mSmarty->assign("pagetitle", $this->mPageTitle);

		$this->mHeaders[] = "Content-Type: text/html; charset=utf-8";
		
		// setup the current page on the menu, but only if the current page 
		// exists on the main menu in the first place
		if(array_key_exists(get_class($this), $this->mMainMenu))
		{
			$this->mMainMenu[get_class($this)]["current"] = true;
		}
		$this->mSmarty->assign("mainmenu", $this->mMainMenu);

		global $cWebPath, $cScriptPath;
		$this->mSmarty->assign("cWebPath", $cWebPath);
		$this->mSmarty->assign("cScriptPath", $cScriptPath);

		// the current page path
		$this->mSmarty->assign("currentPagePath", WebRequest::pathInfo());
		
		$this->mSmarty->assign("subnavigation", $this->mSubMenu);
	}

	/**
	 * Adds the "global" CSS / JS for this part of the system.
	 *
	 * This differs for the management side of the system, hence is overridden over there.
	 * This method is just to make it easier to override.
	 */
	protected function addSystemCssJs()
	{
	}
	
	public function execute()
	{
		Hooks::run("PreSetupPage");
	
		// set up the page
		$this->setupPage();

		try{
			if(!Hooks::run("PreRunPage", array(/* stop! */ false, $this)))
			{
				// "run" the page - allow the user to make any customisations to the
				// current state
				$this->runPage();

				Hooks::run("PostRunPage", array("", $this->mSmarty));
			}
		}
		catch(AccessDeniedException $ex)
		{
			$this->handleAccessDeniedException($ex);
		}
		
		
		// perform any final setup for the page, overwriting any user 
		// customisations which aren't allowed, and anything that potentially 
		// needs to be rebuilt/updated.
		$this->finalSetup();

		Hooks::run("PostFinalSetup");

		try
		{
			// get the page content
			$content = $this->mSmarty->fetch($this->mBasePage);
		}
		catch(SmartyException $ex)
		{
			if(strpos($ex->getMessage(), "Unable to load template file") !== false)
			{
				// throw our own exception so the stack trace comes back here.
				throw new SmartyTemplateNotFoundException(
					$ex->getMessage(),
					$ex->getCode()
					);
			}
		}
		
		// set any HTTP headers
		foreach($this->mHeaders as $h)
		{
			header($h);
		}
		
		// send the cookies to make the client smile and go mmmmm nom nom
		WebRequest::sendCookies();
		
		// send the output
		WebRequest::output($content);
	}

	protected abstract function runPage();

	public static function create()
	{
		// use "Main" as the default
		$page = "Main";

		// check the requested page title for safety and sanity
		
		$pathinfo = explode('/', WebRequest::pathInfo());
		$pathinfo = array_values(array_filter($pathinfo));
		if(
			count($pathinfo) >= 1 &&
			$pathinfo[0] != "" &&  // not empty
			(!ereg("[^a-zA-Z0-9]", $pathinfo[0])) // contains only alphanumeric chars
			)
		{  //if
			$page = $pathinfo[0];
		} // fi
		
		// okay, the page title should be reasonably safe now, let's try and make the page
		
		$pagename = "Page" . $page;
		
		global $cIncludePath, $gLogger;
		$filepath = $cIncludePath . "/Page/" . $pagename . ".php";
		
		if(file_exists($filepath))
		{
			require_once($filepath);
		}
		else
		{	// oops, couldn't find the requested page, let's fail gracefully.
			$pagename = "Page404";
			$filepath = $cIncludePath . "/Page/" . $pagename . ".php";
			require_once($filepath);
		}	

		if(class_exists($pagename))
		{
			$pageobject = new $pagename;
			
			if(get_parent_class($pageobject) == "PageBase")
			{
				Hooks::run("CreatePage", array($pageobject));
				$gLogger->log("Page object created.");
			
				if(! $pageobject->isProtected())
				{
					$gLogger->log("Page object not protected.");
				
					return $pageobject;
				}
				else
				{
					$gLogger->log("Page object IS protected.");
				
					if(Session::isLoggedIn())
					{	
						$gLogger->log("Session is logged in");

						Hooks::register("PreRunPage", 
							function($parameters)
							{
								PageBase::checkAccess($parameters[1]->getAccessName());
								return $parameters[0];
							});
					
						$pageobject = Hooks::run("AuthorisedCreatePage", array($pageobject));
					
						return $pageobject;
					}
					else
					{ // not logged in
						$gLogger->log("Session NOT logged in");

						require_once($cIncludePath . "/Page/PageLogin.php");
						return new PageLogin();
					}
				}
			}
			else
			{
				// defined, but doesn't inherit properly, so we can't guarentee stuff will work.
				throw new Exception();
			}
		}
		else 
		{
			// file exists, but the class "within" doesn't, this is a problem as stuff isn't where it should be.
			throw new Exception();
		}
	}
	
	protected function error($messageTag)
	{
		$this->mSmarty->assign("showError", "yes");
		$this->mSmarty->assign("errortext", $messageTag);
	}

	public function isProtected()
	{
		return $this->mIsProtectedPage;
	}
	
	public function getAccessName()
	{
		return $this->mAccessName;
	}	

	public static function checkAccess($actionName)
	{
		global $gLogger;
		$gLogger->log("Entering checkPageAccessLevel");
			
		$userAccessLevel = User::getById(Session::getLoggedInUser())->isAllowed($actionName);
		
		if($actionName == "public")
		{
			return true;
		}
		else
		{
			if($userAccessLevel) 
			{
				return true;
			}
		}
				
		throw new AccessDeniedException();
	}

	public function handleAccessDeniedException($ex)
	{
		$this->mHeaders = "HTTP/1.1 403 Forbidden";
		$this->mBasePage = "mgmt/accessdenied.tpl";
	}	
}
