<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

abstract class PageBase
{
    // is this page a protected by login page?
    // defaults to false (public access)
    protected $mPageUseRight = "public";

    protected $mPageRegisteredRights = array();

    // is this page a special page which should not be listed?
    protected $mIsSpecialPage = false;

    // menu group
    protected $mMenuGroup = "main";

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

    // personal nav
    protected $mPersonalNav = array();

    // nav bar submenus
    protected $mNavBarItems = array();

    // is this redirecting (hence don't clear session-based stuff like errors)
    protected $mIsRedirecting = false;

    // main menu
    protected $mMainMenu = array();

    // array of HTTP headers to add to the request.
    protected $mHeaders = array();

    public function isSpecialPage() {
        return $this->mIsSpecialPage;
    }

    private function setupSmarty()
    {
        Hooks::run("PreSetupSmarty", array($this->mSmarty) );

        $this->mSmarty = new Smarty();

        $this->mSmarty->registerPlugin(
            "function",
            "message",
            array( "Message", "smartyFuncMessage" )
            );
        
        $this->mSmarty->registerPlugin(
            "function",
            "rawmessage",
            array( "Message", "smartyFuncRawMessage" )
            );

        Hooks::run("PostSetupSmarty", array($this->mSmarty) );
    }

    protected function setupPage()
    {
        global $cScriptPath;

        $this->mMainMenu = array(
            /* Format:
                "Class name" => array(
                    "title" => "Slug used",
                    "displayname" => "Name to show
                    "link" => "Link to show",
                    "items" => array(...)
                    ),
                  */
            "main" => array(
                "title" => "main",
                "items" => array(
                    "PageMain" => array(
                        "title" => "page-main",
                        "link" => "/"
                    )
                ),
                "displayname" => MenuGroup::getBySlug("main")->getDisplayName()
            )
        );

        $this->mPersonalNav = array(
            /* Format:
             "sectiontitle" => array(
                "key" => array(
                    "displayname" => "Title to show",
                    "link" => "Link to show",
                    "icon" => "Icon class to use"
                ),
             ),
             */
            "account" => array(
                "profile" => array(
                    "displayname" => "editprofile",
                    "link" => $cScriptPath . "/EditProfile",
                    "icon" => "icon-tasks",
                ),
                "password" => array(
                    "displayname" => "changepassword",
                    "link" => $cScriptPath . "/ChangePassword",
                    "icon" => "icon-lock",
                ),
            ),
        );

        $this->mNavBarItems = array(
            /* Format:
             "dropdowntitle" => array(
                 "sectiontitle" => array(
                    "key" => array(
                        "displayname" => "Title to show",
                        "link" => "Link to show",
                        "icon" => "Icon class to use"
                    ),
                 ),
             ),
            */
        );

        $this->setupSmarty();

        $this->addSystemCssJs();

        $this->mSmarty->assign("showError", "no");

        if( Session::isLoggedIn() )
        {
            $this->mSmarty->assign("currentUser", User::getLoggedIn() );
        } else {
            $this->mSmarty->assign("currentUser", null );
        }
    }

    protected function finalSetup()
    {
        global $gLogger;
        $gLogger->log("MPage final setup");

        if(Session::getLoggedInUser()) {
            $user = User::getById( Session::getLoggedInUser());
            $this->mSmarty->assign( "loggedin", $user->getUsername() );
            $this->mSmarty->assign( "userfullname", $user->getFullName() );
        } else {
            $this->mSmarty->assign( "loggedin", "" );
        }

        global $cGlobalScripts;
        $scripts = array_merge($cGlobalScripts, $this->mScripts);
        $this->mSmarty->assign("scripts",$scripts);

        global $cGlobalStyles;
        $styles = array_merge($cGlobalStyles, $this->mStyles);
        $this->mSmarty->assign("styles",$styles);

        $this->mSmarty->assign("pagetitle", $this->mPageTitle);

        $this->mHeaders[] = "Content-Type: text/html; charset=utf-8";

        $gLogger->log("   old menu: " . print_r($this->mMainMenu,true));
        $this->mMainMenu = Hooks::run( "PreCreateMenu", array($this->mMainMenu) );
        $gLogger->log("   new menu: " . print_r($this->mMainMenu,true));

        $gLogger->log("   old pmenu: " . print_r($this->mPersonalNav,true));
        $this->mPersonalNav = Hooks::run( "PreCreatePersonalMenu", array($this->mPersonalNav) );
        $gLogger->log("   new pmenu: " . print_r($this->mPersonalNav,true));

        $gLogger->log("   old nmenu: " . print_r($this->mNavBarItems,true));
        $this->mNavBarItems = Hooks::run( "PreCreateNavBarMenu", array($this->mNavBarItems) );
        $gLogger->log("   new nmenu: " . print_r($this->mNavBarItems,true));

        // setup the current page on the menu, but only if the current page
        // exists on the main menu in the first place
        if(array_key_exists(get_class($this), $this->mMainMenu))
        {
            $this->mMainMenu[get_class($this)]["current"] = true;
        }

        // remove empty menu groups
        $newMenu = array();
        foreach ($this->mMainMenu as $k => $menuGroup) {
            if( count( $menuGroup['items'] ) > 0 ) {
                $newMenu[$k] = $menuGroup;
            }
        }
        $this->mMainMenu = $newMenu;

        $this->mSmarty->assign("mainmenu", $this->mMainMenu);
        $this->mSmarty->assign("personalmenu", $this->mPersonalNav);
        $this->mSmarty->assign("navbaritems", $this->mNavBarItems);

        global $cWebPath, $cScriptPath;
        $this->mSmarty->assign("cWebPath", $cWebPath);
        $this->mSmarty->assign("cScriptPath", $cScriptPath);

        // the current page path
        $this->mSmarty->assign("currentPagePath", WebRequest::pathInfo());

        $this->mSmarty->assign("subnavigation", $this->mSubMenu);
        $this->mSmarty->assign("hasSubmenu", count($this->mSubMenu) == 0 ? "no" : "yes" );

        // page slug
        $this->mSmarty->assign( "pageslug", preg_replace( "/^Page(.*)$/", "\${1}", get_class($this) ) );

        // errors:
        $errorlist = Session::getErrorQueue();

        if( User::getLoggedIn()->getPasswordReset() )
        {
            array_unshift( $errorlist, array('message' => "forced-password-reset", 'type' => 'error') );
        }

        $errorlist = Hooks::run("PreErrorDisplay", array($errorlist));

        $this->mSmarty->assign( "sessionerrors", $errorlist );
        if(! $this->mIsRedirecting) {
            Session::clearErrorQueue();
        }
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
            else {
                throw $ex;
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
            (! preg_match( "/[^a-zA-Z0-9]/", $pathinfo[0] ) ) // contains only alphanumeric chars
            )
        {  //if
            $page = $pathinfo[0];
        } // fi

        // okay, the page title should be reasonably safe now, let's try and make the page

        $pagename = "Page" . $page;

        global $cIncludePath, $gLogger;

        $filepath = self::findPageFile($pagename);


        if($filepath !== false)
        { // found it.
            require_once($filepath);
        }
        else
        {    // oops, couldn't find the requested page, let's fail gracefully.
            $extensionContent = self::getExtensionContent( $page );
            if( $extensionContent !== null ) {
                return $extensionContent;
            }

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

    private static function getExtensionContent($page) {
        $result = Hooks::run( "GetExtensionContent", array( null, $page ) );

        if($result !== null)
        {
            return $result;
        }

        return null;
    }

    private static function findPageFile($pagename) {
        global $cIncludePath;

        $pageSearchPaths = array();
        Hooks::register("BuildPageSearchPaths", function($args) {
            global $cIncludePath;
            $args[0][] = $cIncludePath . "/Page/";
            return $args[0];
        });

        $pageSearchPaths = array();
        $result = Hooks::run( "BuildPageSearchPaths", array( $pageSearchPaths ) );
        $pageSearchPaths = array_unique( $result );

        foreach($pageSearchPaths as $path)
        {
            if(file_exists($path . '/' . $pagename . ".php"))
            {
                return $path . '/' . $pagename . ".php";
            }
        }

        return false;
    }

    protected function error($messageTag)
    {
        $this->mSmarty->assign("showError", "yes");
        $this->mSmarty->assign("errortext", $messageTag);
    }

    public function isProtected()
    {
        return $this->mPageUseRight !== "public";
    }

    public function getAccessName()
    {
        return $this->mPageUseRight;
    }

    public function getRegisteredRights() {
        return $this->mPageRegisteredRights;
    }

    public static function checkAccess($actionName) {
        global $gLogger;
        $gLogger->log( "Entering checkPageAccessLevel" );

        if($actionName == "public")
        {
            return true;
        }

        $userAccessLevel = User::getLoggedIn()->isAllowed( $actionName );


        if($userAccessLevel)
        {
            return true;
        }

        throw new AccessDeniedException();
    }

    public function handleAccessDeniedException($ex)
    {
        $this->mHeaders[] = "HTTP/1.1 403 Forbidden";
        $this->mBasePage = "403.tpl";
    }

    public static function getRegisteredPages() {
        global $cIncludePath;

        $pageSearchPaths = array();
        $result = Hooks::run( "BuildPageSearchPaths", array( $pageSearchPaths ) );
        $pageSearchPaths = array_unique( $result );

        $pages = array();

        foreach($pageSearchPaths as $path)
        {
            $filelist = scandir( $path );

            foreach( $filelist as $f ) {
                if( preg_match( "/^Page(.*)\.php$/", $f ) !== 1 ) {
                    continue;
                }

                require_once( $path . $f );

                $f = preg_replace( "/^(.*)\.php$/", "\${1}", $f );
                $obj = new $f();

                if( (! $obj->isSpecialPage() ) ) {
                    $pages[] = $f;
                }
            }
        }

        return $pages;
    }

    public static function getAllPages() {
        global $cIncludePath;

        $pageSearchPaths = array();
        $result = Hooks::run( "BuildPageSearchPaths", array( $pageSearchPaths ) );
        $pageSearchPaths = array_unique( $result );

        $pages = array();

        foreach($pageSearchPaths as $path)
        {
            $filelist = scandir( $path );

            foreach( $filelist as $f ) {
                if( preg_match( "/^Page(.*)\.php$/", $f ) !== 1 ) {
                    continue;
                }

                require_once( $path . $f );

                $f = preg_replace( "/^(.*)\.php$/", "\${1}", $f );
                $pages[] = $f;
            }
        }

        return $pages;
    }

    public function getMenuGroup() {
        return $this->mMenuGroup;
    }
}
