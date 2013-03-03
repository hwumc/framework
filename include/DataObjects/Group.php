<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Group extends DataObject
{
	protected $name;
	protected $description;

	public static function getIdList()
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT id FROM `group`;");
		$statement->execute();

		$result = $statement->fetchAll(PDO::FETCH_COLUMN,0);

		return $result;
	}

	public static function getArray() {
		$output = array();
		$input = Group::getIdList();
		
		foreach( $input as $g ) {
			$group = Group::getById( $g );
			$output[ $g ] = $group->getName();
		}
		
		return $output;
	}
	
	public function save()
	{
		global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO `group` VALUES (null, :name, :desc);");
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
			$statement = $gDatabase->prepare("UPDATE `group` SET name = :name, description = :desc WHERE id = :id LIMIT 1;");
			$statement->bindParam(":name", $this->name);
			$statement->bindParam(":id", $this->id);
			$statement->bindParam(":desc", $this->description);

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
		$statement = $gDatabase->prepare("DELETE FROM `rightgroup` WHERE `group` = :id;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();

	}	
	
	public function getRights() {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT `right` FROM `rightgroup` WHERE `group` = :id;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();
		
		$result = $statement->fetchAll(PDO::FETCH_COLUMN,0);

		return $result;
	}

	public function getName() { return $this->name; }
	public function getDescription() { return $this->description; }
	public function setName( $name ) { $this->name = $name; }
	public function setDescription( $desc ) { $this->description = $desc; }
	
}