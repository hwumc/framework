<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

interface MainPageContentProvider
{
    public function getContent($smarty);

    public function getPageTemplate();
}
