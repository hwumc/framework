<?php

/**
 * ImageGroup short summary.
 *
 * ImageGroup description.
 *
 * @version 1.0
 * @author stwalkerster
 */
class ImageGroup extends DataObject
{
    protected $name;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function clearFiles() {
        global $gDatabase;
        $statement = $gDatabase->prepare( "DELETE FROM `imagegroupfile` WHERE `imagegroup` = :id;" );
        $statement->bindParam( ":id", $this->id );
        $statement->execute();
    }

    public function getFiles() {
        $igf = ImageGroupFile::getByImageGroup($this->id);

        $files = array();
        foreach ($igf as $f)
        {
            $files[] = File::getById($f->getFileId());
        }
        
        return $files;
    }

    public function save() {
        global $gDatabase;

        if($this->isNew)
        {   // insert
            $statement = $gDatabase->prepare("INSERT INTO `imagegroup` (name) VALUES (:name);");
            $statement->bindParam(":name", $this->name);

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
            $statement = $gDatabase->prepare("UPDATE `imagegroup` SET name = :name WHERE id = :id;");
            $statement->bindParam(":name", $this->name);
            $statement->bindParam(":id", $this->id);

            if(!$statement->execute())
            {
                throw new SaveFailedException();
            }
        }
    }
}
