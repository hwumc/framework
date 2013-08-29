<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class DynamicRight extends DataObject
{
    
    protected $right;
    
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
			$statement = $gDatabase->prepare("INSERT INTO dynamicright VALUES (null, :right);");
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