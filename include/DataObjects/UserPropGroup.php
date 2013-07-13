<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class UserPropGroup extends DataObject
{
    protected $name;
    protected $message;
    
    public function getName()
    {
        return $this->name;   
    }

    public function setName($name) 
    {
        $this->name = $name;   
    }
    
    public function getMessageKey()
    {
        return $this->message;   
    }
    
    public function setMessageKey($key)
    {
        $this->message = $key;   
    }
    
    public function save()
    {
        global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO userpropgroup VALUES (null, :name);");
			$statement->bindParam(":name", $this->name);
			if($statement->execute())
			{
				$this->isNew = false;
				$this->id = $gDatabase->lastInsertId();
			}
			else
			{
                global $gDatabase;
				$statement = $gDatabase->prepare("UPDATE userpropgroup SET name = :name WHERE id = :id LIMIT 1;");

				$statement->bindParam(":id", $this->id);
				$statement->bindParam(":name", $this->name);

				if(! $statement->execute())
				{
					throw new SaveFailedException();
				}
			}
		}
		else
		{ // update
			throw new YouShouldntBeDoingThatException();
		}
    }
    
    public function delete()
    {
        global $gDatabase;
		$statement = $gDatabase->prepare("DELETE FROM userpropgroup WHERE id = :id LIMIT 1;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();

		$this->id=0;
		$this->isNew = true;
    }
}
