<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class WebStart
{
	private $managementMode = false;

	public function run()
	{
		$this->setupEnvironment();
		$this->main();
		$this->cleanupEnvironment();
	}
	
	public function setManagementMode()
	{
		$this->managementMode = true;
	}
	
	public static function exceptionHandler($exception)
	{
		$errorDocument = <<<HTML
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8">
<title>Oops! Something went wrong!</title>
<style>
* { margin: 0; padding: 0; }
body { background: #fff; margin: 7% 0 0 7%; padding: 1em 1em 1em; font: 14px/21px sans-serif; color: #333; max-width: 560px; }
img { float: left; margin: 0 2em 2em 0; }
a img { border: 0; }
h1 { margin-top: 1em; font-size: 1.2em; }
p { margin: 0.7em 0 1em 0; }
a { color: #0645AD; text-decoration: none; }
a:hover { text-decoration: underline; }
em { font-style: normal; color: #777; }
p.sub { margin: 0.7em 0 1em 0; font-style: normal; color: #777;
pre {color: #777; font-size: xx-small;}
</style>
</head><body><h1>Oops! Something went wrong!</h1>
<p>We'll work on fixing this for you, so why not come back later?</p>
<p><em>If our trained monkeys ask, tell them this:</em><pre>$1$</pre></p>
</body></html>
HTML;
		$message = "Unhandled " . $exception;
		
		// strip out database passwords from the error message
		global $cMyDotCnfFile;
		$mycnf = parse_ini_file($cMyDotCnfFile);
		
		$message = str_replace($mycnf['user'], "webserver", $message);
		$message = str_replace($mycnf['password'], "sekrit", $message);
		
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

		if(file_exists($cIncludePath . "/ManagementPage/" . $class_name . ".php"))
		{
			require_once($cIncludePath . "/ManagementPage/" . $class_name . ".php");
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
				throw new ExtensionUnavailableException($ext);
			}
		}
	}
	
	private function setupEnvironment()
	{
		global $gDatabase, $cDatabaseConnectionString, $cMyDotCnfFile, 
			$cDatabaseModule, $cIncludePath, $cLoggerName, $gLogger;

		set_exception_handler(array("WebStart","exceptionHandler"));
	
		// check all the required PHP extensions are enabled on this SAPI
		$this->checkPhpExtensions();
	
		// start output buffering before anything is sent to the browser.
		ob_start();
	
		// not caught by the autoloader :(
		require_once('smarty/Smarty.class.php');

		// many exceptions defined in one file, let's not clutter stuff. 
		// This ofc breaks the autoloading, so let's include them all now.
		// (Depends on some smarty stuff)
		require_once($cIncludePath . "/_Exceptions.php");
		
		spl_autoload_register("WebStart::autoLoader");

		Session::start();

		$gLogger = new $cLoggerName;
		$gLogger->log("Initialising logger!");
			
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
		$basePage = $this->managementMode ? "ManagementPageBase" : "PageBase";
	
		// create a page...
		$page = $basePage::create();
		
		// ...and execute it.
		$page->execute();
	}
}
