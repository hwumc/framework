<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

// main configuration file

ini_set('display_errors',1);

$cFilePath = __DIR__;
$cIncludePath = $cFilePath . "/include";
$cScriptPath = $_SERVER['SCRIPT_NAME'];

$pparts = pathinfo($_SERVER["SCRIPT_NAME"]);
$webdirname = str_replace("\\", "/", $pparts['dirname']);
$cWebPath = $webdirname == "/" ? "" : $webdirname;
$cWebPath = str_replace("//","/",$cWebPath);

// database details
$cDatabaseConnectionString = 'mysql:host=dbmaster.srv.stwalkerster.net;dbname=hwumc_new_devel';
$cDatabaseModule = "pdo_mysql";
$cMyDotCnfFile = $cFilePath . "/.my.cnf";
$cMyDotRoDotCnfFile = $cFilePath . "/.my.ro.cnf";

$cLoggerName="FakeLogger";

// array of global scripts to be included
// Global scripts are included first, then local scripts second
$cGlobalScripts = array(
    $cWebPath . '/scripts/jquery-1.9.1.min.js',
    $cWebPath . '/scripts/bootstrap.min.js',
    );

$cGlobalStyles = array(
    $cWebPath . '/style/editor-override.css',
    $cWebPath . '/style/extras.css',
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
    "fileinfo"
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

$cExtensions = array();

$cMainPageContentProvider = "DefaultMainPageContentProvider";

$cAllowUserSqlQueries = false;

$cDisplayDateFormat = "d/m/Y";
$cDisplayDateTimeFormat = "d/m/Y H:i T";
$cDisplayDateTimeFormatNoTz = "d/m/Y H:i";

// Allowed file upload types. Use PHP MIME types here, and remember there could 
// be several variations dependant on browser config. Failures against this list
// are logged in the error log so the list can be adjusted later.
// PLEASE be aware of the security implications of this - certain files (.js, .php, .html, etc)
// are inherently dangerous and should NEVER be allowed. Other files (.svg, .png) can 
// have embedded code which could be unwittingly executed.
//
// Basically, every uploaded file is a security risk. Think long and hard before granting
// access to file uploads.
// 
// Don't mark anything other than JPG and PNG as images or thumbnails will fail to generate
$cAllowedUploadTypes = array(
    "image/png" => "image",
    "image/jpeg" => "image",

    "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => "misc",
    "application/msword" => "misc",
    "application/pdf" => "misc",
    "application/excel" => "misc",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => "misc",
    );

// This is the fully-qualified path to the usercontent script
$cContentScriptWebPath = "/usercontent/content.php";

// this is the on-disk path to wherever the uploads are stored
$cContentFilePath = $cFilePath . "/upload";

$cCmsDefaultTemplate = "cms/cmspage.tpl";
$cCmsTemplates = array(
    "newmainpage.tpl" => "Main Page banner",
    );

// Password encryption options. You shouldn't need to touch this.
// Bump the cost by 1 if you're paranoid or it's 2018 already, but 10 is
// a good baseline for 2016
$gPasswordOptions = array( 'cost' => 10 );

///////////////// don't put new config options below this line

if(file_exists($cFilePath . "/config.local.php"))
{
    require_once($cFilePath . "/config.local.php");
}

// Load the main webstart file
require_once($cIncludePath . "/WebStart.php");
