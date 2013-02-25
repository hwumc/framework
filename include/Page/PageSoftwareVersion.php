<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageSoftwareVersion extends PageBase
{
	public function __construct() {
		$this->mIsProtectedPage = false;
	}

	protected function runPage() {
		$this->mBasePage="webmaster/git.tpl";
		$this->mSmarty->assign( "softwaredesc", exec( "git describe --always --dirty" ) );
		$this->mSmarty->assign( "softwarebranch", str_replace( "refs/heads/", "", exec( 'git symbolic-ref -q HEAD' ) ) );
		$this->mSmarty->assign( "softwaresha", exec( 'git log -n1 --format=%H' ) );
		$this->mSmarty->assign( "softwareorigin", exec( 'git config remote.origin.url' ) );
		
		global $cSoftwareGithubRepo;
		$this->mSmarty->assign( "softwarerepo", $cSoftwareGithubRepo );
	}
}
