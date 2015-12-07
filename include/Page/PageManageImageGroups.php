<?php

/**
 * PageMangeImageGroups short summary.
 *
 * PageMangeImageGroups description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class PageManageImageGroups extends PageBase
{
    public function __construct()
    {
        $this->mPageUseRight = "imagegroups-view";
        $this->mMenuGroup = "Content Management";
        $this->mPageRegisteredRights = array( "imagegroup-edit", "imagegroup-create", "imagegroup-delete" );
    }

    protected function runPage()
    {
        $data = explode( "/", WebRequest::pathInfoExtension() );
        if( isset( $data[0] ) ) {
            switch( $data[0] ) {
                case "edit":
                    $this->editMode( $data );
                    return;
                case "delete":
                    $this->deleteMode( $data );
                    return;
                case "create":
                    $this->createMode( $data );
                    return;
            }

        }

        // try to get more access than we may have.
        try {
            self::checkAccess('imagegroup-create');
            $this->mSmarty->assign("allowCreate", 'true');
        }
        catch(AccessDeniedException $ex) {
            $this->mSmarty->assign("allowCreate", 'false');
        }
        try {
            self::checkAccess('imagegroup-delete');
            $this->mSmarty->assign("allowDelete", 'true');
        }
        catch(AccessDeniedException $ex) {
            $this->mSmarty->assign("allowDelete", 'false');
        }

        $this->mBasePage = "imagegroup/list.tpl";
        $groups = ImageGroup::getArray();
        $this->mSmarty->assign("grouplist", $groups );
    }

    private function editMode( $data ) {
        $allowEdit = "false";
        try {
            self::checkAccess('imagegroup-edit');
            $allowEdit = "true";
        }
        catch(AccessDeniedException $ex) {
            $allowEdit = "false";
        }

        $g = ImageGroup::getById( $data[ 1 ] );

        $this->mSmarty->assign("allowEdit", $allowEdit);

        if( WebRequest::wasPosted() ) {
            if( ! $allowEdit ) throw new AccessDeniedException();

            $g->setName( WebRequest::post( "name" ) );
            $g->save();
            $g->clearFiles();

            $r = array();
            foreach( $_POST as $k => $v ) {
                if( $v !== "on" ) continue;

                if( preg_match( "/^file[0-9]+$/", $k ) === 1 ) {
                    $r[ ] = $k;
                }
            }

            foreach( $r as $k ) {
                $rg = new ImageGroupFile();
                $rg->setImageGroupId( $g->getId() );
                $rg->setFileId( preg_replace( "/^file([0-9]+)$/", "\${1}", $k ) );
                $rg->save();
            }

            global $cScriptPath;
            $this->mHeaders[] = ( "Location: " . $cScriptPath . "/ManageImageGroups" );
            $this->mIsRedirecting = true;
        } else {
            $fileList = File::getImages();
            $imageSelections = array();

            foreach($fileList as $k => $v)
            {
                $imageSelections[$k] = false;
            }

            $existing = ImageGroupFile::getByImageGroup($g->getId());
            foreach($existing as $k => $val)
            {
                $imageSelections[$val->getFileId()] = true;
            }

            $this->mBasePage = "imagegroup/create.tpl";
            $this->mSmarty->assign( "name", $g->getName() );
            $this->mSmarty->assign( "imagelist", $fileList );
            $this->mSmarty->assign( "imageselections", $imageSelections );
        }
    }

    private function deleteMode( $data ) {
        self::checkAccess( "imagegroup-delete" );

        if( WebRequest::wasPosted() ) {
            $g = ImageGroup::getById( $data[1] );
            if( $g !== false ) {
                if( WebRequest::post( "confirm" ) == "confirmed" ) {
                    $g->delete();
                    $this->mSmarty->assign("content", "deleted" );
                }
            }

            global $cScriptPath;
            $this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageImageGroups";
            $this->mIsRedirecting = true;

        } else {
            $this->mBasePage = "imagegroup/delete.tpl";
        }
    }

    private function createMode( $data ) {
        self::checkAccess( "imagegroup-create" );
        $this->mSmarty->assign("allowEdit", 'true');

        if( WebRequest::wasPosted() ) {
            $g = new ImageGroup();
            $g->setName( WebRequest::post( "name" ) );
            $g->save();

            $r = array();
            foreach( $_POST as $k => $v ) {
                if( $v !== "on" ) continue;

                if( preg_match( "/^file[0-9]+$/", $k ) === 1 ) {
                    $r[ ] = $k;
                }
            }

            foreach( $r as $k ) {
                $rg = new ImageGroupFile();
                $rg->setImageGroupId( $g->getId() );
                $rg->setFileId( preg_replace( "/^file([0-9]+)$/", "\${1}", $k ) );
                $rg->save();
            }

            global $cScriptPath;
            $this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageImageGroups";
            $this->mIsRedirecting = true;
        } else {
            $fileList = File::getImages();
            $imageSelections = array();

            foreach($fileList as $k => $v)
            {
                $imageSelections[$k] = false;
            }

            $this->mBasePage = "imagegroup/create.tpl";
            $this->mSmarty->assign( "name", "" );
            $this->mSmarty->assign( "imagelist", $fileList );
            $this->mSmarty->assign( "imageselections", $imageSelections );
        }
    }
}
