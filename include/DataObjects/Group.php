<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Group extends DataObject
{
	protected $name;
	protected $description;
	protected $owner;

	public static function getIdList()
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT id FROM `group`;");
		$statement->execute();

		$result = $statement->fetchAll( PDO::FETCH_COLUMN, 0 );

		return $result;
	}
    
	public static function getByName( $id ) {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM `group` WHERE name = :id LIMIT 1;");
		$statement->bindParam(":id", $id);

		$statement->execute();

		$resultObject = $statement->fetchObject( "Group" );

		if($resultObject != false)
		{
			$resultObject->isNew = false;
		}

		return $resultObject;
	}
    
	public static function getArray() {
		$output = array();
		$input = Group::getIdList();
		
		foreach( $input as $g ) {
			$group = Group::getById( $g );
			$output[ $g ] = $group;
		}
		
		return $output;
	}
	
	public function save()
	{
		global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO `group` VALUES (null, :name, :desc, 0);");
			$statement->bindParam(":name", $this->name);
			$statement->bindParam(":desc", $this->description);
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
			$statement = $gDatabase->prepare("UPDATE `group` SET name = :name, description = :desc, owner = :owner WHERE id = :id LIMIT 1;");
			$statement->bindParam(":name", $this->name);
			$statement->bindParam(":id", $this->id);
			$statement->bindParam(":desc", $this->description);
			$statement->bindParam(":owner", $this->owner);

			if(!$statement->execute())
			{
				throw new SaveFailedException();
			}
		}
	}
	
	public function delete()
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("DELETE FROM `group` WHERE id = :id LIMIT 1;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();

		$this->id=0;
		$this->isNew = true;
	}
	
	public function clearRights() {
		global $gDatabase;
		$statement = $gDatabase->prepare( "DELETE FROM `rightgroup` WHERE `group` = :id;" );
		$statement->bindParam( ":id", $this->id );
		$statement->execute();
	}	
	
	public function getRights() {
		global $gDatabase;
		$statement = $gDatabase->prepare( "SELECT `right` FROM `rightgroup` WHERE `group` = :id;" );
		$statement->bindParam( ":id", $this->id ) ;
		$statement->execute();
		
		$result = $statement->fetchAll( PDO::FETCH_COLUMN, 0 );

		return $result;
	}

	public function getName() { return $this->name; }
	public function getDescription() { return $this->description; }
	public function setName( $name ) { $this->name = $name; }
	public function setDescription( $desc ) { $this->description = $desc; }
	
    public function setOwner( $owner ) {
        // unset
        if( $owner == null ) {
            $this->owner = 0;
            return;
        }
        
        $this->owner = $owner->getId(); 
      
        // special case: myself is 0
        // this means that groups are created owning themselves.
        if( $this->owner == $this->id ) {
            $this->owner = 0;
        }
    }
    
    public function getOwner( ) {
        if( $this->owner == 0 ) {
            return $this;
        }
        
        return Group::getById( $this->owner );
    }
}
