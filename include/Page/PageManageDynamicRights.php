<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageManageDynamicRights extends PageBase
{

    public function __construct()
    {
        $this->mPageUseRight = "dynamicrights-view";
        $this->mMenuGroup = "Users";
        $this->mPageRegisteredRights = array( "dynamicrights-create", "dynamicrights-delete" );

    }

    protected function runPage()
    {
        $data = explode( "/", WebRequest::pathInfoExtension() );
        if( isset( $data[0] ) ) {
            switch( $data[0] ) {
                case "delete":
                    $this->deleteMode( $data );
                    return;
                    break;
                case "create":
                    $this->createMode( $data );
                    return;
                    break;
            }

        }

        // try to get more access than we may have.
        try    {
            self::checkAccess('dynamicrights-create');
            $this->mSmarty->assign("allowCreate", 'true');
        } catch(AccessDeniedException $ex) {
            $this->mSmarty->assign("allowCreate", 'false');
        }
        try {
            self::checkAccess('dynamicrights-delete');
            $this->mSmarty->assign("allowDelete", 'true');
        } catch(AccessDeniedException $ex) {
            $this->mSmarty->assign("allowDelete", 'false');
        }

        $this->mBasePage = "dynamicrights/list.tpl";
        $groups = DynamicRight::getArray();
        $this->mSmarty->assign("grouplist", $groups );
    }

    private function deleteMode( $data ) {
        self::checkAccess( "dynamicrights-delete" );

        if( WebRequest::wasPosted() ) {
            $g = DynamicRight::getById( $data[1] );
            if( $g !== false ) {
                if( WebRequest::post( "confirm" ) == "confirmed" ) {

                    foreach( Rightgroup::getArray() as $rg ) {
                        if( $rg->getRight() == $g->getRight() ) {
                            $rg->delete();
                        }
                    }

                    $g->delete();
                    $this->mSmarty->assign("content", "deleted" );
                }
            }

            global $cScriptPath;
            $this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageDynamicRights";
            $this->mIsRedirecting = true;

        } else {
            $this->mBasePage = "dynamicrights/delete.tpl";
        }
    }

    private function createMode( $data ) {
        self::checkAccess( "dynamicrights-create" );

        if( WebRequest::wasPosted() ) {
            $existing = DynamicRight::getByRight( WebRequest::post( "name" ) );
            if( $existing != null )
            {
                $this->mSmarty->assign( "name", WebRequest::post( "name" ) );
                $this->mBasePage = "dynamicrights/create.tpl";

                Session::appendError("DynamicRights-error-alreadyexists");

                return;
            }

            $g = new DynamicRight();
            $g->setRight( WebRequest::post( "name" ) );
            $g->save();

            global $cScriptPath;
            $this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageDynamicRights";
            $this->mIsRedirecting = true;
        } else {
            $this->mBasePage = "dynamicrights/create.tpl";
            $this->mSmarty->assign( "name", "" );
        }
    }
}
