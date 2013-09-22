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
        $allowEdit = "false";
		try {
			self::checkAccess('groups-edit');
			$allowEdit = "true";
		} catch(AccessDeniedException $ex) { 
            $allowEdit = "false";
		}
		$g = Group::getById( $data[ 1 ] );
        if( $g->isManager( User::getById( Session::getLoggedInUser() ) ) ) {
            $allowEdit = "true";   
        }
        $this->mSmarty->assign("allowEdit", $allowEdit);
        
		if( WebRequest::wasPosted() ) {
			if( ! $allowEdit ) throw new AccessDeniedException();
			
            $parentdescription = WebRequest::post( "parent" );
            $parent = Group::getByName( $parentdescription );
            $g->setOwner( $parent );
			$g->setName( WebRequest::post( "groupname" ) );
			$g->setDescription( WebRequest::post( "description" ) );
			$g->save();
            
            // back up the current rights
            $currentuser = User::getLoggedIn();
            $userrights = $currentuser->getRights();

            // blat this groups rights. After this point, user::isallowed won't work.
			$oldrights = $g->getRights();
			$g->clearRights();
            
			$r = array();
            
            foreach( $oldrights as $right )
            {
                if(! in_array( $right, $userrights ) )
                {
                    // not allowed, re-add.
                    $r[] = $right;
                }
            }
            
			foreach( $_POST as $k => $v ) {
				if( $v !== "on" ) continue;
				
				if( preg_match( "/^right\-.*$/", $k ) === 1 ) {
                    $right = preg_replace( "/^right\-(.*)$/", "\${1}", $k );
                    if( in_array( $right, $userrights ) )
                    {    
                        $r[ ] = $right;
                    }
				}
			}

			foreach( $r as $right ) {
				$rg = new Rightgroup();
				$rg->setGroupID( $g->getId() );
				$rg->setRight( $right );
				$rg->save();
			}
			
			global $cScriptPath;
			$this->mHeaders[] = ( "Location: " . $cScriptPath . "/ManageGroups" );
		} else {
			$rightlist = Right::getAllRegisteredRights();
			$rights = array_combine( $rightlist, array_fill( 0, count( $rightlist ), array('allowed' => "no", 'present' => false) ) );
			
            $users = User::getArray();
            $usersarray = array();
            foreach ($users as $u)
            {
                $usersarray[$u->getUsername()] = $u->inGroup( $g );
            }
            
            $currentuser = User::getLoggedIn();
            foreach( $currentuser->getRights() as $r )
            {
                $rights[ $r ][ 'allowed' ] = "yes";
            }
            
			foreach( $g->getRights() as $r )
            {
				$rights[ $r ]['present'] = "true";
			}
		
			$this->mBasePage = "groups/groupcreate.tpl";
			$this->mSmarty->assign( "rightslist", $rights );
			$this->mSmarty->assign( "userslist", $usersarray );
			$this->mSmarty->assign( "groupname", $g->getName() );
			$this->mSmarty->assign( "description", $g->getDescription() );
            $this->mSmarty->assign( "lockparent", "false" );
            $this->mSmarty->assign( "parent", $g->getOwner()->getName() == $g->getName() ? "" : $g->getOwner()->getName() );
            
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
                    
                    $children = Group::getArray();
                    foreach ($children as $child)
                    {
                        if( $child->getOwner()->getId() == $g->getId() ) {
                            $child->setOwner( $child );
                            $child->save();
                        }
                    }
                    
					$g->delete();
					$this->mSmarty->assign("content", "deleted" );
				}
			}
			
			global $cScriptPath;
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageGroups";
			
			
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
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageGroups";
		} else {
			$rightlist = Right::getAllRegisteredRights();
		
            $users = User::getArray();
            $usersarray = array();
            foreach ($users as $u)
            {
                $usersarray[$u->getUsername()] = $u->getId() == Session::getLoggedInUser() ? true : false;
            }
            
			$this->mBasePage = "groups/groupcreate.tpl";
			$this->mSmarty->assign( "rightslist", array_combine( $rightlist, array_fill( 0, count( $rightlist ), "false" ) ) );
			$this->mSmarty->assign( "userslist", $usersarray );
			$this->mSmarty->assign( "groupname", "" );
			$this->mSmarty->assign( "description", "" );
            $this->mSmarty->assign( "lockparent", "true" );
            $this->mSmarty->assign( "parent", "" );
            $this->mSmarty->assign( "jsgrouplist", "[" . "]" );
		}
	}
}
