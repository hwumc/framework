<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageManageUserPropGroups extends PageBase
{

	public function __construct()
	{
		$this->mPageUseRight = "userprops";
		$this->mMenuGroup = "Users";
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
		
		$this->mBasePage = "userpropgroups/grouppage.tpl";
		$groups = UserPropGroup::getArray();
		$this->mSmarty->assign("grouplist", $groups );
	}
	
	private function deleteMode( $data ) {
	
		if( WebRequest::wasPosted() ) {
			$g = UserPropGroup::getById( $data[1] );
			if( $g !== false ) {
				if( WebRequest::post( "confirm" ) == "confirmed" ) {
					$g->delete();
					$this->mSmarty->assign("content", "deleted" );
				}
			}
			
			global $cScriptPath;
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageUserPropGroups";
		} else {
			$this->mBasePage = "userpropgroups/groupdelete.tpl";
		}
	}
	
	private function createMode( $data ) {
		$this->mSmarty->assign("allowEdit", 'true');
	
		if( WebRequest::wasPosted() ) {
			$g = new UserPropGroup();
			$g->setName( WebRequest::post( "groupname" ) );
			$g->save();
		
            $m = Message::getByName( "userpropgroup-" . WebRequest::post( "groupname" ) . "-description", Message::getActiveLanguage() );
            $m->setContent( WebRequest::post( "groupdesc" ) );
            $m->save();
            
			global $cScriptPath;
			$this->mHeaders[] =  "Location: " . $cScriptPath . "/ManageUserPropGroups";
		} else {
			$this->mBasePage = "userpropgroups/groupcreate.tpl";
		}
	}
}
