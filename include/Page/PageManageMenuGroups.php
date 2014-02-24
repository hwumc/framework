<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageManageMenuGroups extends PageBase
{

	public function __construct()
	{
		$this->mPageUseRight = "menugroup-view";
		$this->mMenuGroup = "Content Management";
		$this->mPageRegisteredRights = array( "menugroup-edit", "menugroup-create", "menugroup-delete" );
		
	}

	protected function runPage()
	{
		$data = explode( "/", WebRequest::pathInfoExtension() );
		if( isset( $data[0] ) ) {
			switch( $data[0] ) {
				case "edit":
					$this->editMode( $data );
					return;
					break;
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
		try	{
			self::checkAccess('menugroup-create');
			$this->mSmarty->assign("allowCreate", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowCreate", 'false');
		} 
		try {
			self::checkAccess('menugroup-delete');
			$this->mSmarty->assign("allowDelete", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowDelete", 'false');
		}
		
		$this->mBasePage = "menugroup/list.tpl";
		$groups = MenuGroup::getArray();
		$this->mSmarty->assign("grouplist", $groups );
	}
	
	private function editMode( $data ) {
        $allowEdit = "false";
		try {
			self::checkAccess('menugroup-edit');
			$allowEdit = "true";
		} catch(AccessDeniedException $ex) { 
            $allowEdit = "false";
		}
		$g = MenuGroup::getById( $data[ 1 ] );
       
        $this->mSmarty->assign("allowEdit", $allowEdit);
        
		if( WebRequest::wasPosted() ) {
			if( ! $allowEdit ) throw new AccessDeniedException();
			
            $exists = MenuGroup::getBySlug( WebRequest::post( "slug" ) );
            if( $exists != null && $exists->getId() != $g->getId() )
            {
                $this->triggerError("slugexists");
                return;
            }
            
			$g->setSlug( WebRequest::post( "slug" ) );
			$g->setDisplayName( WebRequest::post( "displayname" ) );
			$g->save();
			
			global $cScriptPath;
			$this->mHeaders[] = ( "Location: " . $cScriptPath . "/ManageMenuGroups" );
		} else {
			$this->mBasePage = "menugroup/create.tpl";
			$this->mSmarty->assign( "slug", $g->getSlug() );
			$this->mSmarty->assign( "displayname", $g->getDisplayName() );
		}
	}
	
	private function deleteMode( $data ) {
		self::checkAccess( "menugroup-delete" );
	
		if( WebRequest::wasPosted() ) {
			$g = MenuGroup::getById( $data[1] );
			if( $g !== false ) {
				if( WebRequest::post( "confirm" ) == "confirmed" ) {
					$g->delete();
					$this->mSmarty->assign("content", "deleted" );
				}
			}
			
			global $cScriptPath;
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageMenuGroups";
			
		} else {
			$this->mBasePage = "menugroup/delete.tpl";
		}
	}
	
	private function createMode( $data ) {
		self::checkAccess( "menugroup-create" );
		$this->mSmarty->assign("allowEdit", 'true');
	
		if( WebRequest::wasPosted() ) {
            $exists = MenuGroup::getBySlug( WebRequest::post( "slug" ) );
            if( $exists != null && ! $exists->objectIsNew() )
            {
                $this->triggerError("slugexists");
                return;
            }
            
			$g = new MenuGroup();
			$g->setSlug( WebRequest::post( "slug" ) );
			$g->setDisplayName( WebRequest::post( "displayname" ) );
			$g->save();
			            			
			global $cScriptPath;
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageMenuGroups";
		} else {
		
			$this->mBasePage = "menugroup/create.tpl";
			$this->mSmarty->assign( "slug", "" );
			$this->mSmarty->assign( "displayname", "" );
		}
	}
    
    private function triggerError( $errorCode )
    {        
        
        $this->mSmarty->assign( "slug", WebRequest::post( "slug" ) );
        $this->mSmarty->assign( "displayname", WebRequest::post( "displayname" ) );
        
        $this->mBasePage = "menugroup/create.tpl";
        
        Session::appendError("MenuGroup-error-" . $errorCode);
    }
}
