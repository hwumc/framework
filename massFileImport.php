<?php

// define something so we know we've entered the code in the right place.
define("HMS", 1);

if (isset($_SERVER['REQUEST_METHOD'])) {
    die();
} // Web clients die.


// include the configuration file, which should set up the entire environment as we need it.
require_once('config.php');

$importPath = $argv[1];
$copyrightNotice = $argv[2];

echo "Beginning import of files in directory " . $importPath . PHP_EOL;
echo "Using copyright '" . $copyrightNotice . "'" . PHP_EOL;

$fileList = scandir($importPath);

// remove . and ..
if (($key = array_search(".", $fileList)) !== false) {
    unset($fileList[$key]);
}
if (($key = array_search("..", $fileList)) !== false) {
    unset($fileList[$key]);
}

$webStart = new WebStart();
$webStart->setupAutoloader();
$webStart->initialiseLogger();
$webStart->loadExtensions();
$webStart->setupDatabase();

/** @var $gDatabase Database */
global $gDatabase;

$gDatabase->query("SELECT 1 FROM DUAL");

foreach ($fileList as $uploadedPath) {
    echo "Importing " . $uploadedPath.PHP_EOL;

    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $realtype = $fileInfo->file($importPath . $uploadedPath);

    if (!array_key_exists($realtype, $cAllowedUploadTypes)) {
        echo "Upload file of user type $realtype disallowed for file $uploadedPath" . PHP_EOL;
        continue;
    }

    $hash = hash_file("sha256", $importPath . $uploadedPath);

    $existingFile = File::getByChecksum($hash);

    if ($existingFile !== false) {
        echo $uploadedPath . "already exists under the name " . $existingFile->getName().PHP_EOL;
    }

    $f = new File();
    $f->setName($uploadedPath);
    $f->setSize(filesize($importPath.$uploadedPath));
    $f->setChecksum($hash);
    $f->setMime($realtype);
    $f->setCopyright($copyrightNotice);

    $directory = $f->getFilePath(true);
    if (!is_dir($directory)) {
        $result = mkdir($directory, 0755, true);
        if (!$result) {
            echo "Upload directory creation failed.";
            continue;
        }
    }

    rename($importPath . $uploadedPath, $f->getFilePath());

    // Save the file.
    $f->save();
}