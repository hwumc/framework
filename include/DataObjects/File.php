<?php

/**
 * File short summary.
 *
 * File description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class File extends DataObject
{
    protected $name;
    protected $size;
    protected $checksum;
    protected $mime;

    /**
     * Summary of getByChecksum
     * @param string $checksum 
     * @return File|false
     */
    public static function getByChecksum( $checksum ) {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT * FROM `file` WHERE checksum = :id;");
        $statement->bindParam(":id", $checksum);

        $statement->execute();

        $resultObject = $statement->fetchObject( "File" );

        if($resultObject != false)
        {
            $resultObject->isNew = false;
        }

        return $resultObject;
    }

    public static function getImages() {
        global $cAllowedUploadTypes;

        $files = File::getArray();
        $images = array();

        foreach ($files as $id => $obj) {
            if($cAllowedUploadTypes[$obj->getMime()] === "image") {
                $images[$id] = $obj;
            }
        }

        return $images;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getSize(){
        return $this->size;
    }

    public function setSize($size){
        $this->size = $size;
    }

    public function getChecksum(){
        return $this->checksum;
    }

    public function setChecksum($checksum){
        $this->checksum = $checksum;
    }

    public function getMime(){
        return $this->mime;
    }

    public function setMime($mime){
        $this->mime = $mime;
    }

    public function save()
    {
        global $gDatabase;

        if($this->isNew)
        {   // insert
            $statement = $gDatabase->prepare("INSERT INTO `file` (name, size, checksum, mime) VALUES (:name, :size, :checksum, :mime);");
            $statement->bindParam(":name", $this->name);
            $statement->bindParam(":size", $this->size);
            $statement->bindParam(":checksum", $this->checksum);
            $statement->bindParam(":mime", $this->mime);

            if($statement->execute())
            {
                $this->isNew = false;
                $this->id = $gDatabase->lastInsertId();
            }
            else
            {
                throw new SaveFailedException();
            }
        }
        else
        {   // update 
            $statement = $gDatabase->prepare("UPDATE `file` SET name = :name WHERE id = :id;");
            $statement->bindParam(":name", $this->name);
            $statement->bindParam(":id", $this->id);

            if(!$statement->execute())
            {
                throw new SaveFailedException();
            }
        }
    }

    /**
     * Gets the file's real path on disk, relative to index.php
     * 
     * abcdefghijkl, return a/b/cdefghijkl
     * 
     * @return string
     */
    public function getFilePath($dironly = false) {
        global $cContentFilePath;

        $path = $cContentFilePath . "/";
        $path .= substr($this->checksum, 0, 1);
        $path .= "/";
        $path .= substr($this->checksum, 1, 1);
        
        if(!$dironly) {
            $path .= "/";
            $path .= substr($this->checksum, 2);
        }

        return $path;
    }

    public function getDownloadPath(){
        global $cContentScriptWebPath;
        return $cContentScriptWebPath . '/' . $this->getChecksum();
    }

    public function getThumbPath() {
        $status = true;
        if(!file_exists($this->getFilePath() . "_thumb_300")) {
            $status = $this->createThumbnail();
        }

        if($status) {
            return $this->getDownloadPath() . "/thumb";
        }

        return $this->getDownloadPath();
    }

    public static function humanSize($size) {
        $suffix = "B";
        
        if( $size > 1024 ) {
            $size /= 1024;
            $suffix = "KiB";
        }
        
        if( $size > 1024 ) {
            $size /= 1024;
            $suffix = "MiB";
        }
        
        if( $size > 1024 ) {
            $size /= 1024;
            $suffix = "GiB";
        }

        return round($size, 2) . " " . $suffix;
    }

    public function getHumanSize() {
        return self::humanSize($this->size);
    }

    public function delete()
    {
        unlink($this->getFilePath());
        parent::delete();
    }

    /**
     * Splat this due to side effects - canDelete executes a *real* delete followed
     * by a rollback, which works perfectly fine for databases. Not so much for filesystems
     * @return bool
     */
    public function canDelete() {
        return true;
    }

    public function isImage() {
        global $cAllowedUploadTypes;

        return $cAllowedUploadTypes[$this->mime] === "image";
    }

    private function createThumbnail() {
        $imageResource = null;
        $size = 300;

        if($this->getMime() == "image/png") {
            $imageResource = imagecreatefrompng($this->getFilePath());
        }
        else if ($this->getMime() == "image/jpeg") {
            $imageResource = imagecreatefromjpeg($this->getFilePath());
        }
        else {
            return false;
        }

        $oldX = imageSX($imageResource);
        $oldY = imageSY($imageResource);

        if ($oldX > $oldY) {
            $thumbX = $size;
            $thumbY = $oldY * ( $size / $oldX );
        }

        if ($oldX < $oldY) {
            $thumbX = $oldX * ( $size / $oldY );
            $thumbY = $size;
        }

        if ($oldX == $oldY) {
            $thumbX = $size;
            $thumbY = $size;
        }

        $thumbnail = imagecreatetruecolor($thumbX, $thumbY);

        imagecopyresampled($thumbnail, $imageResource, 0, 0, 0, 0, $thumbX, $thumbY, $oldX, $oldY);

        if($this->getMime() == "image/png") {
            imagepng($thumbnail, $this->getFilePath() . "_thumb_" . $size);
        }
        else if ($this->getMime() == "image/jpeg") {
            imagejpeg($thumbnail, $this->getFilePath() . "_thumb_" . $size);
        }

        imagedestroy($thumbnail);
        imagedestroy($imageResource);

        return true;
    }

}
