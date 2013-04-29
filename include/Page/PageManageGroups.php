<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageManageGroups extends PageBase
{

	public function __construct()
	{
		$this->mPageUseRight = "groups-view";
		$this->mMenuGroup = "Users";
		$this->mPageRegisteredRights = array( "groups-edit", "groups-create", "groups-delete" );
		
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
			self::checkAccess('groups-create');
			$this->mSmarty->assign("allowCreate", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowCreate", 'false');
		} 
		try {
			self::checkAccess('groups-delete');
			$this->mSmarty->assign("allowDelete", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowDelete", 'false');
		}
		
		$this->mBasePage = "groups/grouppage.tpl";
		$groups = Group::getArray();
		$this->mSmarty->assign("grouplist", $groups );
	}
	
	private function editMode( $data ) {
		try {
			self::checkAccess('groups-edit');
			$this->mSmarty->assign("allowEdit", 'true');
		} catch(AccessDeniedException $ex) { 
			$this->mSmarty->assign("allowEdit", 'false');
		}
		
		if( WebRequest::wasPosted() ) {
			self::checkAccess( "groups-edit" );
			
			$g = Group::getById( $data[ 1 ] );
            $parentdescription = WebRequest::post( "parent" );
            $parent = Group::getByName( $parentdescription );
            $g->setOwner( $parent );
			$g->setName( WebRequest::post( "groupname" ) );
			$g->setDescription( WebRequest::post( "description" ) );
			$g->save();
			$g->clearRights();
			
			$r = array();
			foreach( $_POST as $k => $v ) {
				if( $v !== "on" ) continue;
				
				if( preg_match( "/^right\-.*$/", $k ) === 1 ) {
					$r[ ] = $k;
				}
			}

			foreach( $r as $k ) {
				$rg = new Rightgroup();
				$rg->setGroupID( $g->getId() );
				$rg->setRight( preg_replace( "/^right\-(.*)$/", "\${1}", $k ) );
				$rg->save();
			}
			
			global $cScriptPath;
			header( "Location: " . $cScriptPath . "/ManageGroups" );
		} else {
			$rightlist = Right::getAllRegisteredRights();
			$rights = array_combine( $rightlist, array_fill( 0, count( $rightlist ), "false" ) );
			$group = Group::getById( $data[ 1 ] );
		
			foreach( $group->getRights() as $r ) {
				$rights[ $r ] = "true";
			}
		
			$this->mBasePage = "groups/groupcreate.tpl";
			$this->mSmarty->assign( "rightslist", $rights );
			$this->mSmarty->assign( "groupname", $group->getName() );
			$this->mSmarty->assign( "description", $group->getDescription() );
            $this->mSmarty->assign( "lockparent", "false" );
            $this->mSmarty->assign( "parent", $group->getOwner()->getName() == $group->getName() ? "" : $group->getOwner()->getName() );
            
            $groupnames= array();
            foreach (Group::getArray() as $k => $v)
            {
                $groupnames[] = "\"" . $v->getName() . "\"";
            }  
            $this->mSmarty->assign( "jsgrouplist", "[" . implode(",", $groupnames ) . "]" );
		}
	}
	
	private function deleteMode( $data ) {
		self::checkAccess( "groups-delete" );
	
		if( WebRequest::wasPosted() ) {
			$g = Group::getById( $data[1] );
			if( $g !== false ) {
				if( WebRequest::post( "confirm" ) == "confirmed" ) {
					$g->delete();
					$this->mSmarty->assign("content", "deleted" );
				}
			}
			
			global $cScriptPath;
			header( "Location: " . $cScriptPath . "/ManageGroups" );
			
			
		} else {
			$this->mBasePage = "groups/groupdelete.tpl";
		}
	}
	
	private function createMode( $data ) {
		self::checkAccess( "groups-create" );
		$this->mSmarty->assign("allowEdit", 'true');
	
		if( WebRequest::wasPosted() ) {
			$g = new Group();
			$g->setName( WebRequest::post( "groupname" ) );
			$g->setDescription( WebRequest::post( "description" ) );
			$g->save();
			
			$r = array();
			foreach( $_POST as $k => $v ) {
				if( $v !== "on" ) continue;
				
				if( preg_match( "/^right\-.*$/", $k ) === 1 ) {
					$r[ ] = $k;
				}
			}

			foreach( $r as $k ) {
				$rg = new Rightgroup();
				$rg->setGroupID( $g->getId() );
				$rg->setRight( preg_replace( "/^right\-(.*)$/", "\${1}", $k ) );
				$rg->save();
			}
            
            // add the current user to this group
            $tug = new Usergroup();
            $tug->setUserID( Session::getLoggedInUser() );
			$tug->setGroupID( $g->getId() );
			$tug->save();
            			
			global $cScriptPath;
			header( "Location: " . $cScriptPath . "/ManageGroups" );
		} else {
			$rightlist = Right::getAllRegisteredRights();
		
			$this->mBasePage = "groups/groupcreate.tpl";
			$this->mSmarty->assign( "rightslist", array_combine( $rightlist, array_fill( 0, count( $rightlist ), "false" ) ) );
			$this->mSmarty->assign( "groupname", "" );
			$this->mSmarty->assign( "description", "" );
            $this->mSmarty->assign( "lockparent", "true" );
            $this->mSmarty->assign( "parent", "" );
            $this->mSmarty->assign( "jsgrouplist", "[" . "]" );
		}
	}
}
