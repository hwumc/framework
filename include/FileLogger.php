<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

/**
 * A fake logging system for production use which actually does nothing.
 */
class FileLogger implements ILogger
{
	public function log($message)
	{
		file_put_contents( "application.log", date("c") . ": " . $message . "\r\n", FILE_APPEND );
	}
}