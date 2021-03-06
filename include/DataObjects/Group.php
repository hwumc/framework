<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Group extends DataObject
{
	protected $name;
	protected $description;
	protected $owner;

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
	
    public static function getWithRight( $right ) 
    {
        global $gDatabase;
		$statement = $gDatabase->prepare("SELECT g.* FROM `group` g INNER JOIN `rightgroup` rg ON rg.`group` = g.`id` WHERE `right` = :right;");
		$statement->bindParam(":right", $right);

		$statement->execute();

		$resultObject = $statement->fetchAll( PDO::FETCH_CLASS, "Group" );

        $data = array();
		foreach ($resultObject as $v)
        {
            $v->isNew = false;
            $data[$v->getId() . ""] = $v;
        }
        
		return $data;
    }
    
	public function save()
	{
		global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO `group` VALUES (null, :name, :desc, null);");
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
            $this->owner = null;
            return;
        }
        
        $this->owner = $owner->getId(); 
      
        // special case: myself is 0
        // this means that groups are created owning themselves.
        if( $this->owner == $this->id ) {
            $this->owner = null;
        }
    }
    
    public function getOwner( ) {
        if( $this->owner == 0 ) {
            return $this;
        }
        
        return Group::getById( $this->owner );
    }
  
    // returns if the specified User is a manager (owner) of this group
    public function isManager( $user ) {
        if( $user->isAllowed( "groups-edit" ) ) {
            return true;   
        }
        
        return ( $user->inGroup( $this->getOwner() ) );
    }
}
