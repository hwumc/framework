<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageSoftwareVersion extends PageBase
{
    public function __construct() {
        $this->mPageUseRight = "sysinfo";
        $this->mMenuGroup = "SystemInfo";
    }

    protected function runPage() {
        $authors = array();
        exec( 'git log --format="%cN <%cE>"', $authors );
        $authors = array_count_values( $authors );
        arsort( $authors );
        $authors = array_keys( $authors);

        $this->mBasePage="webmaster/git.tpl";
        $this->mSmarty->assign( "softwaredesc", exec( "git describe --always --dirty" ) . " (" . exec( 'git log -n1 --pretty=format:"%cD, %cr"' ) . ")" );
        $this->mSmarty->assign( "softwarebranch", str_replace( "refs/heads/", "", exec( 'git symbolic-ref -q HEAD' ) ) );
        $this->mSmarty->assign( "softwaresha", exec( 'git log -n1 --format=%H' ) );
        $this->mSmarty->assign( "softwareorigin", exec( 'git config remote.origin.url' ) );
        $this->mSmarty->assign( "softwareauthors", $authors );

        global $cSoftwareGithubRepo;
        $this->mSmarty->assign( "softwarerepo", $cSoftwareGithubRepo );

        global $cRequiredPhpExtensions;
        $this->mSmarty->assign( "phpextensions", $cRequiredPhpExtensions);

        global $gDatabase;
        $this->mSmarty->assign( "pdodriver", $gDatabase->getAttribute(PDO::ATTR_DRIVER_NAME));
        $this->mSmarty->assign( "pdoserverversion", $gDatabase->getAttribute(PDO::ATTR_SERVER_VERSION));

        global $gLoadedExtensions;
        $this->mSmarty->assign( "extensions", $gLoadedExtensions );
        $this->mSmarty->assign( "extensionsCount", count($gLoadedExtensions) );
    }
}
