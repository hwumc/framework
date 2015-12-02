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
        // smarty would be nice to use, but it COULD BE smarty that throws the errors.
        // Let's build something ourselves, and hope it works.
        global $cWebPath;

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

        // clean any secret variables, including the first 15 chars of the database password (yay php.)
        $message = str_replace($mycnf['user'], "webserver", $message);
        $message = str_replace($mycnf['password'], "sekrit", $message);
        $message = str_replace(substr($mycnf['password'], 0, 15) . '...', "sekrit", $message);
        $message = str_replace($cFilePath, "", $message);

        // clear and discard any content that's been saved to the output buffer
        ob_end_clean();

        global $cWebPath;

        // push exception into the document.
        $message = str_replace('$1$', $message, $errorDocument);

        // output the document
        print $message;

        file_put_contents( $cFilePath . "/errorlog/" . microtime(true) . "_" . session_id(), serialize( $exception ) );

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

        //throw new Exception("File not found");
    }

    private function checkPhpExtensions()
    {
        global $cRequiredPhpExtensions;

        foreach($cRequiredPhpExtensions as $ext)
        {
            if(!extension_loaded($ext))
            {
                throw new ExtensionUnavailableException("The required PHP extension $ext is not installed.");
            }
        }
    }

    private function setupAutoloader()
    {
        global $cIncludePath;

        // not caught by the autoloader :(
        require_once('smarty/Smarty.class.php');

        // many exceptions defined in one file, let's not clutter stuff.
        // This ofc breaks the autoloading, so let's include them all now.
        // (Depends on some smarty stuff)
        require_once($cIncludePath . "/_Exceptions.php");

        // register our autoloader
        spl_autoload_register("WebStart::autoLoader");
    }

    private function setupDatabase()
    {
        global $gDatabase, $gReadOnlyDatabase, $cDatabaseConnectionString, $cMyDotCnfFile, $cMyDotRoDotCnfFile,
            $cDatabaseModule;

        Hooks::run("PreSetupDatabase");

        if(!extension_loaded($cDatabaseModule))
        {
            throw new ExtensionUnavailableException($cDatabaseModule);
        }

        if($cMyDotCnfFile === false)
        {
            $mycnf = array( "user" => null, "password" => null);
            $myrocnf = array( "user" => null, "password" => null);
        }
        else 
        {
            $mycnf = parse_ini_file($cMyDotCnfFile);
            $myrocnf = parse_ini_file($cMyDotRoDotCnfFile);
        }
        
        $gDatabase = new Database($cDatabaseConnectionString, $mycnf["user"], $mycnf["password"]);
        $gReadOnlyDatabase = new Database($cDatabaseConnectionString, $myrocnf["user"], $myrocnf["password"]);

        // test the database connected successfully
        if($gDatabase->query("SELECT 'ping';")->fetchColumn() !== "ping")
        {
            $errorInfo = $gDatabase->errorInfo();
            throw new Exception("Database connection failed.");
        }

        // tidy up sensitive data we don't want lying around.
        unset($mycnf);
        unset($myrocnf);

        // use exceptions on failed database stuff
        $gDatabase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $gReadOnlyDatabase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        Hooks::run("PostSetupDatabase", array( $gDatabase ) );
        Hooks::run("PostSetupDatabase", array( $gReadOnlyDatabase ) );
    }

    private function initialiseLogger()
    {
        global $cLoggerName, $gLogger;
        $gLogger = new $cLoggerName;
        $gLogger->log("Initialising logger for path " . $_SERVER["REQUEST_URI"]);
    }

    private function loadExtensions()
    {
        global $cExtensions, $gLogger, $gLoadedExtensions;

        $gLoadedExtensions = array();

        foreach ($cExtensions as $class => $file)
        {
            if( class_exists($class) ) continue;

            if( file_exists( $file ) )
            {
                require_once( $file );
                if( class_exists($class) && array_key_exists("Extension", class_parents($class) ))
                {
                    $ext = new $class();
                    $ext->setup();
                    $gLoadedExtensions[] = $ext;
                }
                else
                {
                    $gLogger->log("Could not load extension $class: class does not exist or does not inherit Extension.");
                }
            }
            else
            {
                $gLogger->log("Could not load extension $class: $file does not exist.");
            }
        }

    }

    /**
     * Setup the environment ready to start main execution.
     *
     * Assume nothing is running yet.
     * @return
     */
    private function setupEnvironment()
    {
        set_exception_handler(array("WebStart","exceptionHandler"));

        // start output buffering before anything is sent to the browser.
        ob_start();

        $this->setupAutoloader();

        // check all the required PHP extensions are enabled on this SAPI
        $this->checkPhpExtensions();

        // initialise logger.
        $this->initialiseLogger();

        // load extensions
        $this->loadExtensions();

        // initialise database
        $this->setupDatabase();

        // **** At this point, everything should be loaded to run normally.

        Hooks::run("PreSessionStart");
        // start session management
        Session::start();
        Hooks::run("PostSessionStart");



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
