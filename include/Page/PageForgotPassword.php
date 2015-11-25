<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageForgotPassword extends PageBase
{

    public function __construct()
    {
        $this->mIsSpecialPage = true;
    }

    protected function runPage()
    {
        $this->mBasePage = "forgotpassword/main.tpl";

        if(WebRequest::wasPosted()) // sanity check
        {
            if( $username = WebRequest::postString( "lgUser" ) ) {
                $cust = User::getByNameOrEmail($username);
                if($cust != null) {
                    if( $cust->isMailConfirmed() ) {
                        $cust->sendPasswordReset();
                    }
                }
            }



            global $cScriptPath;
            // redirect back to the main page.
            $this->mHeaders[] = "HTTP/1.1 303 See Other";
            $this->mHeaders[] = "Location: " . $cScriptPath . "/ForgotPassword/sent";
            $this->mIsRedirecting = true;
        }
        else
        {
            $data = explode( "/", WebRequest::pathInfoExtension() );
            if( isset( $data[0] ) && $data[0] == "sent" ) {
                $this->mBasePage = "forgotpassword/sent.tpl";
            }
        }
    }
}
