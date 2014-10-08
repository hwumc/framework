<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class DynamicRight extends DataObject
{
    protected $right;

    public static function getByRight($right)
    {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT * FROM `" . strtolower( get_called_class() ) . "` WHERE `right` = :id LIMIT 1;");
        $statement->bindParam(":id", $right);

        $statement->execute();

        $resultObject = $statement->fetchObject( get_called_class() );

        if($resultObject != false)
        {
            $resultObject->isNew = false;
        }

        return $resultObject;
    }

    public function getRight()
    {
        return $this->right;
    }

    public function setRight($right)
    {
        if(!$this->isNew)
        {
            throw new YouShouldntBeDoingThatException();
        }

        $this->right = $right;
    }

    public function save()
    {
        global $gDatabase;

        if($this->isNew)
        { // insert
            $statement = $gDatabase->prepare("INSERT INTO dynamicright (`right`) VALUES (:right);");
            $statement->bindParam(":right", $this->right);
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
        { // update
            throw new YouShouldntBeDoingThatException();
        }
    }
}