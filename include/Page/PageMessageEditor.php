<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageMessageEditor extends PageBase
{
    public function __construct()
    {
        $this->mPageUseRight = "messages-view";
        $this->mMenuGroup = "SystemInfo";
        $this->mPageRegisteredRights = array( "messages-edit", "messages-clear", "messages-delete" );
    }

    protected function runPage()
    {
        // try to get more access than we may have.
        try    {
            self::checkAccess('messages-clear');
            self::checkAccess('messages-delete');
            $this->mSmarty->assign("allowTruncate", 'true');
        } catch(AccessDeniedException $ex) {
            $this->mSmarty->assign("allowTruncate", 'false');
        }
        try    {
            self::checkAccess('messages-delete');
            $this->mSmarty->assign("allowDelete", 'true');
        } catch(AccessDeniedException $ex) {
            $this->mSmarty->assign("allowDelete", 'false');
        }

        $this->mSmarty->assign( "pager", $this->makePager() );
        $this->mSmarty->assign("showtable", 0);
        $this->mSmarty->assign("content", "erm...");

        $data = explode( "/", WebRequest::pathInfoExtension() );

        if( $data[0] === "clear" ) {
            self::checkAccess( "messages-clear" );
            self::checkAccess( "messages-delete" );

            if( WebRequest::wasPosted() ) {
                Message::clearAll();

                $this->mBasePage = "blank.tpl";
                global $cScriptPath;
                $this->mHeaders[] =  "Location: " . $cScriptPath . "/MessageEditor";
                $this->mIsRedirecting = true;
            } else {
                $this->mBasePage = "messageeditor/clear.tpl";
            }

            return;
        }

        if( $data[0] === "rmunset" ) {
            self::checkAccess( "messages-clear" );

            if( WebRequest::wasPosted() ) {
                $this->mBasePage = "blank.tpl";
                Message::clearAllUnset();
                global $cScriptPath;
                $this->mHeaders[] =  "Location: " . $cScriptPath . "/MessageEditor";
                $this->mIsRedirecting = true;
            } else {
                $this->mBasePage = "messageeditor/rmunset.tpl";
            }

            return;
        }



        if( WebRequest::wasPosted() ) {
            self::checkAccess('messages-edit');

            global $cScriptPath;
            $this->mHeaders[] = "HTTP/1.1 303 See Other";
            $this->mHeaders[] = "Location: " . $cScriptPath . "/MessageEditor";
            $this->mIsRedirecting = true;

            $this->save();

            $this->mSmarty->assign("content", "<pre>".print_r($_POST,true)."</pre>");
            return;
        } else {

            $this->mBasePage = "messageeditor/messages.tpl";

            // try to get more access than we may have.
            try    {
                self::checkAccess('messages-edit');
                $this->mSmarty->assign("readonly", '');
            } catch(AccessDeniedException $ex) { // nope, catch the error and handle gracefully
                // caution: if you're copying this, this is a hack to make sure
                //            users know they don't have the access to do this, not
                //             to actually stop them from doing it, though it will have
                //             that effect to the non-tech-savvy.
                $this->mSmarty->assign("readonly", 'disabled="disabled"');
            }

            $keys = array();
            $filterUnset = false;
            if(WebRequest::get("showall"))
            {
                if(WebRequest::get("showall") == "unset")
                {
                    $filterUnset = true;
                }

                $keys = Message::getMessageKeys();

            }
            else
            {
                if(WebRequest::get("prefix"))
                {
                    $keys = Message::getMessageKeys();
                    $keys = array_filter($keys, function($value){
                        $prefix = WebRequest::get("prefix");
                        return (strtolower(substr($value, 0, strlen($prefix))) == strtolower($prefix));
                    });
                }
            }


            if(count($keys) > 0)
            {
                $this->mSmarty->assign("showtable", 1);
                global $cAvailableLanguages;

                // retrieve the message table as an array (of message keys) of arrays
                // (of languages) of arrays (of id/current content)
                $messagetable = array();
                foreach($keys as $mkey)
                {
                    $completelySet = true;
                    $messagetable[$mkey] = array();
                    foreach($cAvailableLanguages as $lang => $langname)
                    {
                        $message = Message::getByName($mkey, $lang);
                        if($message->getContent() == "<$lang:$mkey>")
                        {
                            if($lang==Message::getActiveLanguage())
                            {
                                $completelySet = false;
                            }
                        }
                        $messagetable[$mkey][$lang] = array(
                            "id" => $message->getId(),
                            "content" => $message->getContent()
                            );
                    }

                    if($filterUnset && $completelySet)
                    {
                        unset($messagetable[$mkey]);
                    }
                }

                $this->mSmarty->assign("languagetable", $messagetable);
                $this->mSmarty->assign("languages",$cAvailableLanguages);
            }
            else
            {
                $this->mSmarty->assign("showtable", 0);
            }
        }
    }

    private function makePager() {
        $values = array(
            "A" => array( "title" => "A", "class" => "", "count" => 0 ),
            "B" => array( "title" => "B", "class" => "", "count" => 0 ),
            "C" => array( "title" => "C", "class" => "", "count" => 0 ),
            "D" => array( "title" => "D", "class" => "", "count" => 0 ),
            "E" => array( "title" => "E", "class" => "", "count" => 0 ),
            "F" => array( "title" => "F", "class" => "", "count" => 0 ),
            "G" => array( "title" => "G", "class" => "", "count" => 0 ),
            "H" => array( "title" => "H", "class" => "", "count" => 0 ),
            "I" => array( "title" => "I", "class" => "", "count" => 0 ),
            "J" => array( "title" => "J", "class" => "", "count" => 0 ),
            "K" => array( "title" => "K", "class" => "", "count" => 0 ),
            "L" => array( "title" => "L", "class" => "", "count" => 0 ),
            "M" => array( "title" => "M", "class" => "", "count" => 0 ),
            "N" => array( "title" => "N", "class" => "", "count" => 0 ),
            "O" => array( "title" => "O", "class" => "", "count" => 0 ),
            "P" => array( "title" => "P", "class" => "", "count" => 0 ),
            "Q" => array( "title" => "Q", "class" => "", "count" => 0 ),
            "R" => array( "title" => "R", "class" => "", "count" => 0 ),
            "S" => array( "title" => "S", "class" => "", "count" => 0 ),
            "T" => array( "title" => "T", "class" => "", "count" => 0 ),
            "U" => array( "title" => "U", "class" => "", "count" => 0 ),
            "V" => array( "title" => "V", "class" => "", "count" => 0 ),
            "W" => array( "title" => "W", "class" => "", "count" => 0 ),
            "X" => array( "title" => "X", "class" => "", "count" => 0 ),
            "Y" => array( "title" => "Y", "class" => "", "count" => 0 ),
            "Z" => array( "title" => "Z", "class" => "", "count" => 0 ),
            );

        global $gDatabase, $gLogger;
        $gLogger->log( "preparing query" );

        $search = $gDatabase->prepare( "SELECT COUNT(*) FROM message WHERE name LIKE :lower OR name LIKE :upper;" );
        $counts = array();

        foreach( $values as $k => $v ) {
            $gLogger->log( "exec query for $k" );
            $search->execute( array( ":lower" => strtolower( $k ) . "%", ":upper" => $k . "%" ) );
            $counts[ $k ] = $search->fetchColumn();
            $gLogger->log( "  count: " . $counts[ $k ] );
        }

        foreach( $counts as $k => $v ) { $values[ $k ][ "count" ] = $v; }

        $filter = (string)WebRequest::get( "prefix" );
        if( array_key_exists( $filter, $values ) ) {
            $values[ $filter ][ "class" ] = "active";
        }

        return $values;
    }

    private function save()
    {
        $keys = WebRequest::getPostKeys();

        $saveQ = array();

        foreach($keys as $k)
        {
            $matchdata = array();
            if( preg_match( "/delete-(.*)/", $k, $matchdata ) )
            {
                // check we have the needed access
                self::checkAccess('messages-delete');

                // delete key
                Message::clearAllKey( $matchdata[1] );

                // skip save of everything else.
                return;
            }

            // extract id from POST request
            $id = str_replace( "lang", "", $k);
            $id = str_replace( "msg", "", $id);
            if( ! is_numeric( $id ) )
            {
                throw new ArgumentException("$k: [$id] is not an integer", 0);
            }

            // retrieve message object
            $message = Message::getById( $id );
            if($message == null)
            {
                throw new ArgumentException("Message ID $id could not be found");
            }

            $value=WebRequest::post($k);

            if($message->getContent() != $value)
            {
                // write content
                $message->setContent($value);

                // queue message to be saved - we can skip this later
                $saveQ[] = $message;
            }
        }

        // actually save the messages
        foreach( $saveQ as $message ) {
            $message->save();
        }
    }
}
