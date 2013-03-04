<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class User extends DataObject
{
	protected $username;
	protected $password;
	protected $fullName;
	protected $experience;
	protected $medical;
	protected $emergcontact;
	protected $emergcontactphone;
	protected $mobile;
	protected $email;
	protected $emailconfirmation;
	protected $godmode;

	public static function getIdList()
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT id FROM user;");
		$statement->execute();

		$result = $statement->fetchAll(PDO::FETCH_COLUMN,0);

		return $result;
	}	
	
	public static function getArray() {
		$output = array();
		$input = User::getIdList();
		
		foreach( $input as $g ) {
			$output[ $g ] = User::getById( $g );
		}
		
		return $output;
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
			$statement = $gDatabase->prepare("INSERT INTO user VALUES (null, :username, :password, :fullName, :experience, :medical, :emergcontact, :emergcontactphone, :mobile, :email, :emailconfirmation, :godmode);");
			$statement->bindParam(":username", $this->username);
			$statement->bindParam(":password", $this->password);
			$statement->bindParam(":fullName", $this->fullName);
			$statement->bindParam(":experience", $this->experience);
			$statement->bindParam(":medical", $this->medical);
			$statement->bindParam(":emergcontact", $this->emergcontact);
			$statement->bindParam(":emergcontactphone", $this->emergcontactphone);
			$statement->bindParam(":mobile", $this->mobile);
			$statement->bindParam(":email", $this->email);
			$statement->bindParam(":emailconfirmation", $this->emailconfirmation);
			$statement->bindParam(":godmode", 0); // force to zero - we don't 
								//want godmode users created without good reason.
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
			$statement = $gDatabase->prepare("UPDATE user SET username = :username, password = :password, fullName = :fullName, experience = :experience, medical = :medical, emergcontact = :emergcontact, emergcontactphone = :emergcontactphone, mobile = :mobile, email = :email, emailconfirmation = :emailconfirmation WHERE id = :id LIMIT 1;");
			$statement->bindParam(":username", $this->username);
			$statement->bindParam(":password", $this->password);
			$statement->bindParam(":fullName", $this->fullName);
			$statement->bindParam(":experience", $this->experience);
			$statement->bindParam(":medical", $this->medical);
			$statement->bindParam(":emergcontact", $this->emergcontact);
			$statement->bindParam(":emergcontactphone", $this->emergcontactphone);
			$statement->bindParam(":mobile", $this->mobile);
			$statement->bindParam(":email", $this->email);
			$statement->bindParam(":emailconfirmation", $this->emailconfirmation);
			// not including godmode here. It should never be changed from the interface.
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
	
	public function getFullName()
	{
		return $this->fullName;
	}

	public function setFullName($fullName)
	{
		if( $fullName === "" ) return;
		
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
	public function getEmergencyContactPhone()
	{
		return $this->emergcontactphone;
	}

	public function setEmergencyContactPhone($emergcontact)
	{
		$this->emergcontactphone = $emergcontact;
	}

	public function getMobile()
	{
		return $this->mobile;
	}

	public function setMobile($mobile)
	{
		if( $mobile === "" ) return;
	
		$this->mobile = $mobile;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function isAllowed($action)
	{
		if( $this->isGod() ) {
			return true;
		}
	
		// TODO: fix me
		return in_array( $action, $this->getRights() );
	}
	public function isMailConfirmed()
	{
		// TODO: fix me
		return true;
	}	
	
	public function isGod()
	{
		return $this->godmode;
	}
	
	public static function addMenuItems( $menu ) {
		$menu = $menu[0];
		$user = User::getById( Session::getLoggedInUser());
		
		global $gLogger;
		
		foreach( PageBase::getRegisteredPages() as $pc ) {
			$page = new $pc();
			$pagename = preg_replace( "/^Page(.*)$/", "\${1}", $pc );
			$gLogger->log("Menu generator: Using URL name of page class $pc as $pagename");
			
			if( ( ! $page->isProtected() ) || ( $user->isAllowed( $page->getAccessName() ) ) ) {
			
				$group = $page->getMenuGroup();
				$groupkey = "menu-" . strtolower( $group );
				
				if( ! isset( $menu[ $group ] ) ) {
					$menu[ $group ] = array(
						"items" => array(),
						"title" => $groupkey,
					);
				}
				
				$menu[ $group ][ "items" ][ $pc ] = array(
					"title" => "page-" . strtolower( $pagename ),
					"link" => "/" . $pagename,
				);
			} else {
				$gLogger->log("Page $pc is protected, not adding entry.");
			}
			
		}
				
		return $menu;
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
	
	public function getGroups() {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT g.*, 'false' as isNew FROM usergroup ug INNER JOIN `group` g ON g.id = ug.`group` WHERE ug.user = :id;");
		$statement->bindParam(":id", $this->id);

		$statement->execute();

		$resultObject = $statement->fetchAll( PDO::FETCH_CLASS, "Group" );
		
		return $resultObject;
	}
	
	public function clearGroups() {
		global $gDatabase;
		$statement = $gDatabase->prepare("DELETE FROM `usergroup` WHERE `user` = :id;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();
	}
	
	public function getRights() {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT DISTINCT rightgroup.right FROM rightgroup INNER JOIN `group` ON `group`.id = rightgroup.`group` INNER JOIN usergroup ON `group`.id = usergroup.`group` WHERE usergroup.user = :id;");
		$statement->bindParam(":id", $this->id);

		$statement->execute();

		$resultObject = $statement->fetchAll( PDO::FETCH_COLUMN, 0 );
		
		return $resultObject;
	}
}
