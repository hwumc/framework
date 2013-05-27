<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class WebStart
{
	public function run()
	{
		$this->setupEnvironment();
		$this->main();
		$this->cleanupEnvironment();
	}
	
	public static function exceptionHandler($exception)
	{
		$errorDocument = <<<HTML
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8">
<title>Oops! Something went wrong!</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="{$cWebPath}/style/bootstrap.min.css" rel="stylesheet">
<style>
  body {
	padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
  }
</style>
<link href="{$cWebPath}/style/bootstrap-responsive.min.css" rel="stylesheet">
</head><body><div class="container">
<h1>Oops! Something went wrong!</h1>
<p>We'll work on fixing this for you, so why not come back later?</p><p class="muted">If our trained monkeys ask, tell them this:</p><pre>$1$</pre>
</div></body></html>
HTML;
		$message = "Unhandled " . $exception;
		
		// strip out database passwords from the error message
		global $cMyDotCnfFile, $cFilePath;
		$mycnf = parse_ini_file($cMyDotCnfFile);
		
		$message = str_replace($mycnf['user'], "webserver", $message);
		$message = str_replace($mycnf['password'], "sekrit", $message);
		$message = str_replace($cFilePath, "", $message);
		
		ob_end_clean();
		
		global $cWebPath;
		
		print str_replace('$1$', $message, $errorDocument);
		die;
	}
	
	private static function autoLoader($class_name)
	{
		global $cIncludePath;
		if(file_exists($cIncludePath . "/" . $class_name . ".php"))
		{
			require_once($cIncludePath . "/" . $class_name . ".php");
			return;
		}
		
		if(file_exists($cIncludePath . "/Page/" . $class_name . ".php"))
		{
			require_once($cIncludePath . "/Page/" . $class_name . ".php");
			return;
		}
		
		if(file_exists($cIncludePath . "/DataObjects/" . $class_name . ".php"))
		{
			require_once($cIncludePath . "/DataObjects/" . $class_name . ".php");
			return;
		}
		
		throw new Exception("File not found");
	}	

	private function checkPhpExtensions()
	{
		global $cRequiredExtensions;
		
		foreach($cRequiredExtensions as $ext)
		{
			if(!extension_loaded($ext))
			{
				throw new ExtensionUnavailableException("The required PHP extension $ext is not installed.");
			}
		}
	}
	
	private function setupEnvironment()
	{
		global $gDatabase, $cDatabaseConnectionString, $cMyDotCnfFile, 
			$cDatabaseModule, $cIncludePath, $cLoggerName, $gLogger;

		set_exception_handler(array("WebStart","exceptionHandler"));
	
		// start output buffering before anything is sent to the browser.
		ob_start();
	
		// not caught by the autoloader :(
		require_once('smarty/Smarty.class.php');

		// many exceptions defined in one file, let's not clutter stuff. 
		// This ofc breaks the autoloading, so let's include them all now.
		// (Depends on some smarty stuff)
		require_once($cIncludePath . "/_Exceptions.php");
		
		spl_autoload_register("WebStart::autoLoader");
        
		// check all the required PHP extensions are enabled on this SAPI
		$this->checkPhpExtensions();

		Session::start();

		$gLogger = new $cLoggerName;
		$gLogger->log("Initialising logger for path " . $_SERVER["REQUEST_URI"]);
			
		if(!extension_loaded($cDatabaseModule))
		{
			throw new ExtensionUnavailableException($cDatabaseModule);
		}
		
		$mycnf = parse_ini_file($cMyDotCnfFile);
		
		$gDatabase = new Database($cDatabaseConnectionString,$mycnf["user"], $mycnf["password"]);
		
		// tidy up sensitive data we don't want lying around.
		unset($mycnf);
		
		// use exceptions on failed database stuff
		$gDatabase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// can we tidy up the output with tidy before we send it?
		if(extension_loaded("tidy"))
		{ // Yes!
		
			global $cUseTidy;
			if($cUseTidy)
			{
				// register a new function to hook into the output bit
				Hooks::register("BeforeOutputSend", function($params) {
						$tidy = new Tidy();
						global $cTidyOptions;
						return $tidy->repairString($params[0], $cTidyOptions, "utf8");
					});
			}
		}
		
		
		
		global $gCookieJar;
		$gCookieJar = array();
	}
	
	protected function cleanupEnvironment()
	{
		// discard any extra content
		ob_end_clean();
		
		global $gLogger;
		$gLogger->log("Shutting down system...");
	}
	
	protected function main()
	{
		// create a page...
		$page = PageBase::create();
		
		// ...and execute it.
		$page->execute();
	}
}
