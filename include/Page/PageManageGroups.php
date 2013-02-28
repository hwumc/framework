<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageManageGroups extends PageBase
{

	public function __construct()
	{
		$this->mPageUseRight = "viewgroups";
		$this->mMenuGroup = "Users";
		$this->mPageRegisteredRights = array( "editgroups" );
		
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
	
	}
	
	private function deleteMode( $data ) {
	
	}
	
	private function createMode( $data ) {
	
	}
}
