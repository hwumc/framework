<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Usergroup extends DataObject
{
	protected $user;
	protected $group;

	public function save()
	{
		global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO usergroup VALUES (null, :user, :group);");
			$statement->bindParam(":user", $this->user);
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
	
	public function delete()
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("DELETE FROM usergroup WHERE id = :id LIMIT 1;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();

		$this->id=0;
		$this->isNew = true;
	}
	
	public function getGroupID() { return $this->group; }
	public function getUserID() { return $this->user; }
	public function getGroup() { return Group::getById( $this->group ); }
	public function getUser() { return User::getById( $this->user ); }
	public function setGroupID( $group ) { $this->group = $group; }
	public function setUserID( $user ) { $this->user = $user; }
}
