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
		$this->mSmarty->assign("grouplist", array("foo") );
	}
	
	private function editMode( $data ) {
		$this->mBasePage = "groups/groupedit.tpl";
		self::checkAccess( "groups-edit" );
	}
	
	private function deleteMode( $data ) {
		$this->mBasePage = "groups/groupdelete.tpl";
		self::checkAccess( "groups-delete" );
	}
	
	private function createMode( $data ) {
		$this->mBasePage = "groups/groupcreate.tpl";
		self::checkAccess( "groups-create" );
		
		$this->mSmarty->assign("rightslist", Right::getAllRegisteredRights() );
	}
}
