<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Rightgroup extends DataObject
{
    protected $right;
    protected $group;

    public function save()
    {
        global $gDatabase;

        if($this->isNew)
        { // insert
            $statement = $gDatabase->prepare("INSERT INTO rightgroup (`group`, `right`) VALUES (:group, :right);");
            $statement->bindParam(":right", $this->right);
            $statement->bindParam(":group", $this->group);
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

    public function getGroupID() { return $this->group; }
    public function getGroup() { return Group::getById( $this->group ); }
    public function getRight() { return $this->right; }
    public function setGroupID( $group ) { $this->group = $group; }
    public function setRight( $right ) { $this->right = $right; }

}
