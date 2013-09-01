<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class DefaultMainPageContentProvider implements MainPageContentProvider
{
	public function getContent($smarty) {
        return "content";
    }
    
    public function getPageTemplate() {
        return "base.tpl";
    }
}
