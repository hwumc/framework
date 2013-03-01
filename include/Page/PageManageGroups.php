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
		
		$this->mBasePage = "groups/grouppage.tpl";
		$groups = Group::getArray();
		$this->mSmarty->assign("grouplist", $groups );
	}
	
	private function editMode( $data ) {
		self::checkAccess( "groups-edit" );
		
		if( WebRequest::wasPosted() ) {
			$g = Group::getById( $data[ 1 ] );
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
		
			$this->mBasePage = "groups/groupcreate.tpl";
			$this->mSmarty->assign("rightslist", array_combine( $rightlist, array_fill( 0, count( $rightlist ), "false" ) ) );
			$this->mSmarty->assign("groupname", Group::getById( $data[ 1 ] )->getName() );
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
	
		if( WebRequest::wasPosted() ) {
			$g = new Group();
			$g->setName( WebRequest::post( "groupname" ) );
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
			
			global $cScriptPath;
			header( "Location: " . $cScriptPath . "/ManageGroups" );
		} else {
			$rightlist = Right::getAllRegisteredRights();
		
			$this->mBasePage = "groups/groupcreate.tpl";
			$this->mSmarty->assign("rightslist", array_combine( $rightlist, array_fill( 0, count( $rightlist ), "false" ) ) );
			$this->mSmarty->assign("groupname", "" );
		}
	}
}
