<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class MenuGroup extends DataObject
{
    protected $slug;
    protected $displayname;
    
    public static function getBySlug( $slug ) {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM `menugroup` WHERE slug = :id LIMIT 1;");
		$statement->bindParam(":id", $slug);

		$statement->execute();

		$resultObject = $statement->fetchObject( "MenuGroup" );

		if($resultObject != false)
		{
			$resultObject->isNew = false;
		}

		return $resultObject;
	}
    
    public function getSlug() {
        return $this->slug;
    }
    
    public function setSlug($slug) {
        $this->slug = $slug;
    }
    
    public function getDisplayName(){
        return $this->displayname;
    }
    
    public function setDisplayName($displayName) {
        $this->displayname = $displayName;
    }
    
    public function delete() {
        global $gDatabase;
		$statement = $gDatabase->prepare("DELETE FROM menugroup WHERE id = :id LIMIT 1;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();

		$this->id=0;
		$this->isNew = true;
    }
    
    public function save()
    {
        global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO menugroup VALUES (null, :slug, :displayname);");
			$statement->bindParam(":slug", $this->slug);
			$statement->bindParam(":displayname", $this->displayname);
            
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
            $statement = $gDatabase->prepare("UPDATE `menugroup` SET slug = :slug, displayname = :displayname WHERE id = :id LIMIT 1;");
			$statement->bindParam(":id", $this->id);
			$statement->bindParam(":slug", $this->slug);
			$statement->bindParam(":displayname", $this->displayname);

			if(!$statement->execute())
			{
				throw new SaveFailedException();
			}
		}
    }
}
