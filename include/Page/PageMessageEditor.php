<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class PageMessageEditor extends PageBase
{
	public function __construct()
	{
		$this->mPageUseRight = "messages-view";
		$this->mMenuGroup = "SystemInfo";
		$this->mPageRegisteredRights = array( "messages-edit", "messages-clear" );
	}

	protected function runPage()
	{
		$this->mBasePage = "messageeditor/messages.tpl";
		$this->mSmarty->assign( "allowTruncate", "true" );
		$this->mSmarty->assign( "pager", $this->makePager() );
	//	$this->mSmarty->assign( "content", print_r($this->makePager()) );
	
	}

	private function makePager() {
		$values = array( 
			"0" => array( "title" => "0-9", "class" => "", "count" => 0 ),
			"A" => array( "title" => "A", "class" => "", "count" => 0 ),
			"B" => array( "title" => "B", "class" => "", "count" => 0 ),
			"C" => array( "title" => "C", "class" => "", "count" => 0 ),
			"D" => array( "title" => "D", "class" => "", "count" => 0 ),
			"E" => array( "title" => "E", "class" => "", "count" => 0 ),
			"F" => array( "title" => "F", "class" => "", "count" => 0 ),
			"G" => array( "title" => "G", "class" => "", "count" => 0 ),
			"H" => array( "title" => "H", "class" => "", "count" => 0 ),
			"I" => array( "title" => "I", "class" => "", "count" => 0 ),
			"J" => array( "title" => "J", "class" => "", "count" => 0 ),
			"K" => array( "title" => "K", "class" => "", "count" => 0),
			"L" => array( "title" => "L", "class" => "", "count" => 0 ),
			"M" => array( "title" => "M", "class" => "", "count" => 0 ),
			"N" => array( "title" => "N", "class" => "", "count" => 0 ),
			"O" => array( "title" => "O", "class" => "", "count" => 0 ),
			"P" => array( "title" => "P", "class" => "", "count" => 0 ),
			"Q" => array( "title" => "Q", "class" => "", "count" => 0 ),
			"R" => array( "title" => "R", "class" => "", "count" => 0 ),
			"S" => array( "title" => "S", "class" => "", "count" => 0 ),
			"T" => array( "title" => "T", "class" => "", "count" => 0 ),
			"U" => array( "title" => "U", "class" => "", "count" => 0 ),
			"V" => array( "title" => "V", "class" => "", "count" => 0 ),
			"W" => array( "title" => "W", "class" => "", "count" => 0 ),
			"X" => array( "title" => "X", "class" => "", "count" => 0 ),
			"Y" => array( "title" => "Y", "class" => "", "count" => 0 ),
			"Z" => array( "title" => "Z", "class" => "", "count" => 0 ),
			"other" => array( "title" => "...", "class" => "", "count" => 0 ),
			);
	
		global $gDatabase, $gLogger;
		$gLogger->log( "preparing query" );
		
		$search = $gDatabase->prepare( "SELECT COUNT(*) FROM message WHERE name LIKE :lower OR name LIKE :upper;" );
		$counts = array();
		
		foreach( $values as $k => $v ) {
			if( $k === "0" || $k === "other" ) continue;
			$gLogger->log( "exec query for $k" );
			$search->execute( array( ":lower" => strtolower( $k ) . "%", ":upper" => $k . "%" ) );
			$counts[ $k ] = $search->fetchColumn();
			$gLogger->log( "  count: " . $counts[ $k ] );
		}
		
		foreach( $counts as $k => $v ) { $values[ $k ][ "count" ] = $v; }
	
		$filter = (string)WebRequest::get( "filter" );
		if( array_key_exists( $filter, $values ) ) {
			$values[ $filter ][ "class" ] = "active";
		}
	
		return $values;
	}
	
	
}
