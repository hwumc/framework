<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

// main configuration file

ini_set('display_errors',1);

$cIncludePath = "include";
$cFilePath = __DIR__;
$cScriptPath = $_SERVER['SCRIPT_NAME'];

$pparts = pathinfo($_SERVER["SCRIPT_NAME"]);
$webdirname = str_replace("\\", "/", $pparts['dirname']);
$cWebPath = $webdirname == "/" ? "" : $webdirname;
$cWebPath = str_replace("//","/",$cWebPath);

// database details
$cDatabaseConnectionString = 'mysql:host=dbmaster.srv.stwalkerster.net;dbname=hwumc_new_devel';
$cDatabaseModule = "pdo_mysql";
$cMyDotCnfFile = ".my.cnf";

$cLoggerName="FakeLogger";

// array of global scripts to be included
// Global scripts are included first, then local scripts second
$cGlobalScripts = array(
	$cWebPath . '/scripts/jquery-1.9.1.min.js',
	$cWebPath . '/scripts/bootstrap.min.js',
	);

$cGlobalStyles = array(
	);
	
// Languages accepted by the system
$cAvailableLanguages = array(
	'zxx' => "(Language Tag Codes)",
	'en-GB' => "English (British)",
	);

// Default language for the site to use
$cDefaultLanguage = 'en-GB';
	
// list of required php extensions.
// The PDO module required is set above, and need not be listed here also.
// Optional ones such as Tidy should not be listed here - the site will run 
// without them. 
$cRequiredPhpExtensions = array(
	"PDO",
	"SPL",
	"OpenSSL",
	"pcre",
	"session",
	"date",
	);
	
// use Tidy to make pretty HTML.
$cUseTidy = false;
	
$cTidyOptions = array(
	//"hide-comments" => 1, // discards html comments
	"logical-emphasis" => 1, // swaps <b> for <strong> and <i> for <em>
	"output-xhtml" => 1,
	"indent" => "auto",
	"wrap" => 0, // disables wrapping
	"vertical-space" => 1, // adds vertical spacing for readability
	);
	
$cSoftwareGithubRepo = "stwalkerster/siteframework";

$cExtensions = array();

///////////////// don't put new config options below this line

if(file_exists("config.local.php"))
{
	require_once("config.local.php");
}

// Load the main hotel file
require_once($cIncludePath . "/WebStart.php");
