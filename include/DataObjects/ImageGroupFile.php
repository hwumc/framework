<?php

/**
 * ImageGroupFile short summary.
 *
 * ImageGroupFile description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class ImageGroupFile extends DataObject
{
    protected $imagegroup;
    protected $file;

    /**
     * Summary of getByImageGroup
     * @param int $id
     * @return ImageGroupFile[]
     */
    public static function getByImageGroup($id) {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT * FROM `imagegroupfile` WHERE imagegroup = :id;");
        $statement->bindParam(":id", $id);

        $statement->execute();

        $resultObject = $statement->fetchAll( PDO::FETCH_CLASS, "ImageGroupFile" );

        $data = array();
        foreach ($resultObject as $v)
        {
            $v->isNew = false;
            $data[$v->getId() . ""] = $v;
        }

        return $data;
    }

    public function getImageGroupId() {
        return $this->imagegroup;
    }

    public function setImageGroupId($imagegroup) {
        $this->imagegroup = $imagegroup;
    }

    public function getFileId() {
        return $this->file;
    }

    public function setFileId($file) {
        $this->file = $file;
    }

    public function save() {
        global $gDatabase;

        if($this->isNew)
        {   // insert
            $statement = $gDatabase->prepare("INSERT INTO `imagegroupfile` (imagegroup, file) VALUES (:imagegroup, :file);");
            $statement->bindParam(":imagegroup", $this->imagegroup);
            $statement->bindParam(":file", $this->file);
            
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
            throw new YouShouldntBeDoingThatException();
        }

    }
}
