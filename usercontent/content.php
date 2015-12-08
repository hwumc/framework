<?php
// This is the user content code entry point.
// There should be very little code in this file, as it should just provide an entry point to the rest of the code.

// define something so we know we've entered the code in the right place.
define("HMS",1);

// include the configuration file, which should set up the entire environment as we need it.
require_once('../config.php');

// set up the environment
$application = new WebStart();
$application->setupEnvironment();

$file = File::getByChecksum(substr(WebRequest::pathInfo(), 1));

if($file === false) {
    header("HTTP/1.1 404 Not Found");
    return;
}

$skipFile = false;

if("\"" . $file->getChecksum() . "\"" == $_SERVER['HTTP_IF_NONE_MATCH'])
{
    header("HTTP/1.1 304 Not Modified");
    $skipFile = true;
}

header("ETag: \"{$file->getChecksum()}\"");
header("Cache-Control: max-age=31536000");
header("Content-Type: " . $file->getMime());
header("Content-Disposition: attachment");

if(!$skipFile) {
    readfile($file->getFilePath());
}
