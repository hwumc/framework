<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageManageUsers extends PageBase
{

	public function __construct()
	{
		$this->mPageUseRight = "users-view";
		$this->mMenuGroup = "Users";
		$this->mPageRegisteredRights = array( "users-edit", "users-delete" );
		
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
			}
	
		}
		
		$this->mBasePage = "users/userlist.tpl";
		$users = User::getArray();
		$this->mSmarty->assign("userlist", $users );
	}
	
	private function editMode( $data ) {
		self::checkAccess( "users-edit" );
	
		$this->mBasePage = "users/useredit.tpl";
		
	
		if( WebRequest::wasPosted() ) {
			$u = User::getById( $data[ 1 ] );
			$u->setUsername( WebRequest::post( "username" ) );
			$u->setFullName( WebRequest::post( "realname" ) );
			$u->setEmail( WebRequest::post( "email" ) );
			$u->save();
			
			//$g->clearRights();
			/*
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
			*/
			
			global $cScriptPath;
			$this->mBasePage = "blank.tpl";
			$this->mHeaders[] = "HTTP/1.1 303 See Other";
			$this->mHeaders[] = "Location: " . $cScriptPath . "/ManageUsers";
		} else {
			$groupList = Group::getArray();
			$groups = array();
			
			foreach( $groupList as $id => $g) {
				$groups[ $id ] = array(
					"name" => $g->getName(),
					"description" => $g->getDescription(),
					"assigned" => false );
			}
			
			$user = User::getById( $data[ 1 ] );
		
			foreach( $user->getGroups() as $id => $g ) {
				$groups[ $id ][ "assigned" ] = true;
			}

			
			$this->mSmarty->assign( "grouplist", $groups );
			$this->mSmarty->assign( "user", $user );
		}
	}
	
	private function deleteMode( $data ) {
		self::checkAccess( "users-delete" );
	
		$this->mBasePage = "users/userdelete.tpl";
		/*
	
	
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
	*/}
}
