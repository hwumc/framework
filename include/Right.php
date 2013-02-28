<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Right
{
	public static function getAllRegisteredRights() {
		$rights = array();
		
		foreach( PageBase::getRegisteredPages() as $p ) {
			$page = new $p();
			$pr = $page->getRegisteredRights();
			$pr[] = $page->getAccessName();
		
			$rights = array_merge( $rights, $pr );
		}
		
		$rights = array_unique( $rights );
		sort($rights);
		
		return $rights;
	}
}