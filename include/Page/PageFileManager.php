<?php

/**
 * PageFileManager short summary.
 *
 * PageFileManager description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class PageFileManager extends PageBase
{
    public function __construct()
    {
        $this->mPageUseRight = "files-view";
        $this->mMenuGroup = "Content Management";
        $this->mPageRegisteredRights = array( "files-upload", "files-delete" );
    }

    protected function runPage()
    {
        $data = explode( "/", WebRequest::pathInfoExtension() );
        if( isset( $data[0] ) ) {
            switch( $data[0] ) {
                case "upload":
                    $this->uploadMode( );
                    return;

                case "delete":
                    $this->deleteMode( $data );
                    return;
            }
        }

        try {
            self::checkAccess('files-delete');
            $this->mSmarty->assign("allowDelete", 'true');
        }
        catch(AccessDeniedException $ex) {
            $this->mSmarty->assign("allowDelete", 'false');
        }

        try {
            self::checkAccess('files-upload');
            $this->mSmarty->assign("allowUpload", 'true');
        }
        catch(AccessDeniedException $ex) {
            $this->mSmarty->assign("allowUpload", 'false');
        }

        $this->mBasePage = "files/list.tpl";
        $files = File::getArray();
        $this->mSmarty->assign("filelist", $files );
    }

    private function uploadMode() {
        self::checkAccess( "files-upload" );

        if( WebRequest::wasPosted() ) {
            try {
                global $cAllowedUploadTypes, $cFilePath;

                if(! isset($_FILES['file']) || !is_array($_FILES['file'])) {
                    // no file uploaded?
                    throw new UploadFailedException("upload-no-file");
                }

                $uploadedPath = $_FILES['file']['tmp_name'];

                // **********************************************************************************
                // HEY! You!
                //
                // Yes, you. Don't be tempted to trust these values!
                // $_FILES['file']['name'];
                $usersize = $_FILES['file']['size'];
                $usertype = $_FILES['file']['type'];
                // ... or anything else apart from tmp_name.
                // ... and not even tmp_name without passing it through is_uploaded_file.
                // **********************************************************************************
                
                if($usersize === 0) {
                    // no file uploaded?
                    throw new UploadFailedException("upload-no-file");
                }

                if(! is_uploaded_file($uploadedPath) ) {
                    gErrorLog("Upload Security Exploit detected - tmp_name is not an uploaded file: " . print_r($_FILES, true));
                    throw new UploadFailedException("upload-security-exploit");
                }

                $fileInfo = new finfo(FILEINFO_MIME_TYPE);
                $realtype = $fileInfo->file($uploadedPath);

                if(! array_key_exists($realtype, $cAllowedUploadTypes)) {
                    gErrorLog("Upload file of user type $usertype disallowed, detected type $realtype.");
                    throw new UploadFailedException("upload-disallowed-file-type");
                }

                // OK, so the file probably (isn't) safe by now, so let's save it.

                $hash = hash_file( "sha256", $uploadedPath );

                $existingFile = File::getByChecksum( $hash );

                if($existingFile !== false) {
                    throw new UploadFailedException("upload-file-exists");
                }

                $f = new File();
                $f->setName( WebRequest::post( "filename" ) );
                $f->setSize( filesize($uploadedPath) );
                $f->setChecksum( $hash );
                $f->setMime( $realtype );

                $directory = $f->getFilePath(true);
                if( ! is_dir( $directory ) ) {
                    $result = mkdir($directory, 0755, true);
                    if(!$result) {
                        throw new UploadFailedException("upload-dir-creation-failed");
                    }
                }

                $result = move_uploaded_file($uploadedPath, $f->getFilePath());
                if(!$result) {
                    throw new UploadFailedException("upload-file-move-failed");
                }

                // Save the file.
                $f->save();

                global $cScriptPath;
                $this->mHeaders[] =  "Location: " . $cScriptPath . "/FileManager";
                $this->mIsRedirecting = true;
            }
            catch( UploadFailedException $ex) {
                Session::appendError($ex->getMessage());

                global $cScriptPath;
                $this->mHeaders[] =  "Location: " . $cScriptPath . "/FileManager/upload";
                $this->mIsRedirecting = true;
            }
        } else {
            $this->mSmarty->assign("maxSize", $this->getMaxUploadSize());
            $this->mSmarty->assign("humanMaxSize", File::humanSize($this->getMaxUploadSize()));
            $this->mBasePage = "files/upload.tpl";
        }
    }

    private function deleteMode( $data ) {
        self::checkAccess( "files-delete" );

        if( WebRequest::wasPosted() ) {
            $g = File::getById( $data[1] );
            if( $g !== false ) {
                if( WebRequest::post( "confirm" ) == "confirmed" ) {
                    $g->delete();
                    $this->mSmarty->assign("content", "deleted" );
                }
            }

            global $cScriptPath;
            $this->mHeaders[] =  "Location: " . $cScriptPath . "/FileManager";
            $this->mIsRedirecting = true;
        } else {
            $g = File::getById( $data[1] );
            if( $g !== false ) {
                $this->mSmarty->assign("file", $g );
            }
            else {
                throw new Exception("File not found");
            }

            $this->mBasePage = "files/delete.tpl";
        }
    }

    private function getMaxUploadSize() {
        static $max_size = -1;

        if ($max_size < 0) {
            $max_size = $this->parseIniSize(ini_get('post_max_size'));
            $upload_max = $this->parseIniSize(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    private function parseIniSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }
}
