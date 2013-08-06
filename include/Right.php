<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Right
{
	public static function getAllRegisteredRights($includePseudoRights = false) {
		$rights = array();
		
		foreach( PageBase::getRegisteredPages() as $p ) {
			$page = new $p();
			$pr = $page->getRegisteredRights();
			$pr[] = $page->getAccessName();
		
			$rights = array_merge( $rights, $pr );
		}
        
		if($includePseudoRights) {
            $rights = array_merge( $rights, self::getAllPseudoRights() );
        }
		
        $rights = array_unique( $rights );
		sort($rights);
		
		return $rights;
	}
    
    private static function getAllPseudoRights() {
        return array(
            "public",
            "user",
            );
    }
}