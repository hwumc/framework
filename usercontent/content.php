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

function validateCallPath() {
    $requestUri = WebRequest::getRequestUri();
    $requestPathData = parse_url($requestUri);
    $requestPathData['host'] = $_SERVER['SERVER_NAME'];

    global $cContentScriptWebPath;
    $correctPathData = parse_url($cContentScriptWebPath);

    if(isset($correctPathData['host']) && $correctPathData['host'] != $requestPathData['host']) {
        return false;
    }

    if(substr($requestPathData['path'], -strlen($correctPathData['path'])) !== $correctPathData['path']) {
        return false;
    }

    return true;
}

$isvalid = validateCallPath();
if(!$isvalid) {
    header("HTTP/1.1 403 Forbidden");
}

$pathInfo = WebRequest::pathInfo();
$pathInfo = substr($pathInfo, 1);
$parts = explode("/", $pathInfo);

$file = File::getByChecksum($parts[0]);

if($file === false) {
    header("HTTP/1.1 404 Not Found");
    return;
}

function sendFile($diskPath, $hash, $mime) {
    $skipFile = false;

    if("\"" . $hash . "\"" == $_SERVER['HTTP_IF_NONE_MATCH'])
    {
        header("HTTP/1.1 304 Not Modified");
        $skipFile = true;
    }

    header("ETag: \"{$hash}\"");
    header("Cache-Control: max-age=31536000");
    header("Content-Type: " . $mime);
    header("Content-Disposition: attachment");

    if(!$skipFile) {
        readfile($diskPath);
    }
}

if(isset($parts[1])){
    // Special.
    if($parts[1] == "thumb") {
        $filepath = $file->getFilePath() . "_thumb_300";
        $hash = hash_file( "sha256", $filepath );

        sendFile($filepath, $hash, $file->getMime());
    }
    else{
        // idk.
        header("HTTP/1.1 404 Not Found");
        return;
    }
}
else {
    // normal file.
    sendFile($file->getFilePath(), $file->getChecksum(), $file->getMime());
}