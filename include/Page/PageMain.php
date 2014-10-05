<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageMain extends PageBase
{
    public function __construct()
    {
    }

    protected function runPage()
    {
        global $cMainPageContentProvider;
        $contentProvider = new $cMainPageContentProvider();
        $content = $contentProvider->getContent($this->mSmarty);
        $this->mBasePage = $contentProvider->getPageTemplate();
        $this->mSmarty->assign("content", $content);
    }
}
