<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class User extends DataObject
{
	protected $username;
	protected $password;
	protected $userGroup;
	protected $fullName;
	protected $experience;
	protected $medical;
	protected $emergcontact;
	protected $mobile;
	protected $emailconfirmation;

	public static function getIdList()
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT id FROM user;");
		$statement->execute();

		$result = $statement->fetchAll(PDO::FETCH_COLUMN,0);

		return $result;
	}

	public static function getById($id)
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM user WHERE id = :id LIMIT 1;");
		$statement->bindParam(":id", $id);

		$statement->execute();

		$resultObject = $statement->fetchObject("User");
		if($resultObject != false)
		{
			$resultObject->isNew = false;
		}
		return $resultObject;
	}

	public static function getByName($name)
	{
		global $gLogger;
		$gLogger->log("User: getting $name from database");
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM user WHERE username = :username LIMIT 1;");
		$statement->bindParam(":username", $name);

		$statement->execute();

		$resultObject = $statement->fetchObject("User");
		if($resultObject != false)
		{
			$gLogger->log("User::getByName: $name exists");
			$resultObject->isNew = false;
		}

		return $resultObject;
	}

	/**
	 * Check the stored password against the provided password
	 * @returns true if the password is correct
	 */
	public function authenticate($password)
	{
		global $gLogger;
		$encpass = self::encryptPassword($this->username, $password);
		$gLogger->log("User::authenticate: Comparing {$this->password} to {$encpass}");
		return ( $this->password == $encpass);
	}

	// let's not make a decrypt method.... we don't need it.
	protected static function encryptPassword($username, $password)
	{
		// simple encryption. MD5 is very easy to compute, and very hard to reverse.
		// As it's easy to compute, people make tables of possible values to decrypt
		// it (see: Rainbow Tables). We completely nerf that by adding a known 
		// changable factor to the hash, known as a salt. This makes rainbow
		// tables practically useless against this set of passwords.
		return md5(md5($username . md5($password)));
	}

	public function save()
	{
		global $gDatabase;

		if($this->isNew)
		{ // insert
			$statement = $gDatabase->prepare("INSERT INTO internaluser VALUES (null, :username, :password, :userGroup, :fullName, :experience, :medical, :emergcontact, :mobile, :emailconfirmation);");
			$statement->bindParam(":username", $this->username);
			$statement->bindParam(":password", $this->password);
			$statement->bindParam(":userGroup", $this->userGroup);
			$statement->bindParam(":fullName", $this->fullName);
			$statement->bindParam(":experience", $this->experience);
			$statement->bindParam(":medical", $this->medical);
			$statement->bindParam(":emergcontact", $this->emergcontact);
			$statement->bindParam(":mobile", $this->mobile);
			$statement->bindParam(":emailconfirmation", $this->emailconfirmation);
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
			$statement = $gDatabase->prepare("UPDATE internaluser SET username = :username, password = :password, userGroup = :userGroup, fullName = :fullName, experience = :experience, medical = :medical, emergcontact = :emergcontact, mobile = :mobile, emailconfirmation = :emailconfirmation WHERE id = :id LIMIT 1;");
			$statement->bindParam(":username", $this->username);
			$statement->bindParam(":password", $this->password);
			$statement->bindParam(":userGroup", $this->userGroup);
			$statement->bindParam(":fullName", $this->fullName);
			$statement->bindParam(":experience", $this->experience);
			$statement->bindParam(":medical", $this->medical);
			$statement->bindParam(":emergcontact", $this->emergcontact);
			$statement->bindParam(":mobile", $this->mobile);
			$statement->bindParam(":emailconfirmation", $this->emailconfirmation);
			$statement->bindParam(":id", $this->id);

			if(!$statement->execute())
			{
				throw new SaveFailedException();
			}
		}
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setPassword($password)
	{
		$this->password = self::encryptPassword($this->username, $password);
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

		public function getUserGroup()
	{
		return $this->userGroup;
	}

	public function setUserGroup($userGroup)
	{
		$this->userGroup = $userGroup;
	}

	public function getFullName()
	{
		return $this->fullName;
	}

	public function setFullName($fullName)
	{
		$this->fullName = $fullName;
	}

	public function getExperience()
	{
		return $this->experience;
	}

	public function setExperience($experience)
	{
		$this->experience = $experience;
	}

	public function getMedical()
	{
		return $this->medical;
	}

	public function setMedical($medical)
	{
		$this->medical = $medical;
	}

	public function getEmergencyContact()
	{
		return $this->emergcontact;
	}

	public function setEmergencyContact($emergcontact)
	{
		$this->emergcontact = $emergcontact;
	}

	public function getMobile()
	{
		return $this->mobile;
	}

	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
	}

	public function isAllowed($action)
	{
		// TODO: fix me
		return true;
	}
	public function isMailConfirmed()
	{
		// TODO: fix me
		return true;
	}
	
	
	public function delete()
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("DELETE FROM user WHERE id = :id LIMIT 1;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();

		$this->id=0;
		$this->isNew = true;
	}
}
