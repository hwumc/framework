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

        if( WebRequest::wasPosted() )
        {
            try
            {
                $this->mBasePage = "blank.tpl";

                if( $user->authenticate( WebRequest::post( "old" ) ) )
                {
                    if( WebRequest::post( "new" ) == "" || WebRequest::post( "new" ) === false )  throw new Exception("login-nopass");
                    if( WebRequest::post( "confirm" ) != WebRequest::post( "new" ) )  throw new Exception("login-mismatch");

                    $user->setPassword( WebRequest::post( "new" ) );
                    $user->setPasswordReset( 0 );

                    $user->save();
                }
                else
                {
                    throw new Exception("login-invalid");
                }

                global $cScriptPath;
                $this->mHeaders[] = "HTTP/1.1 303 See Other";
                $this->mHeaders[] = "Location: " . $cScriptPath . "/EditProfile";
            }
            catch(Exception $ex)
            {
                Session::appendError($ex->getMessage());

                $this->mIsRedirecting = true;

                global $cScriptPath;
                $this->mHeaders[] = "HTTP/1.1 303 See Other";
                $this->mHeaders[] = "Location: " . $cScriptPath . "/ChangePassword" . ( WebRequest::get( "forced" ) == "yes" ? "?forced=yes" : "" );
            }
        }
        else
        {
            Hooks::register("PreErrorDisplay", function($errorlist)
            {
                $list = array();
                foreach ($errorlist[0] as $e)
                {
                    if($e != "forced-password-reset")
                        $list[] = $e;
                }

                return $list;
            });

            if( WebRequest::get( "forced" ) == "yes" )
            {
                $this->mBasePage = "profile/loginchpw.tpl";
            }
            else
            {
                $this->mBasePage = "profile/chpw.tpl";
            }
        }

    }
}
