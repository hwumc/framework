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
    protected $isdriver = 0;
    protected $profilereview = 0;
    protected $driverexpiry = null;
    protected $passwordreset = 0;

    public static function getLoggedIn()
    {
        $user = User::getById( Session::getLoggedInUser() );

        if($user === false) {
            $user = new AnonymousUser();
        }

        return $user;
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

    public static function getWithRight( $right )
    {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT DISTINCT u.* FROM `group` g INNER JOIN `rightgroup` rg ON rg.`group` = g.`id` INNER JOIN `usergroup` ug ON g.`id` = ug.`group` INNER JOIN `user` u ON u.`id` = ug.`user` WHERE `right` = :rght;");
        $statement->bindParam(":rght", $right);

        $statement->execute();

        $resultObject = $statement->fetchAll( PDO::FETCH_CLASS, "User" );

        $data = array();
        foreach ($resultObject as $v)
        {
            $v->isNew = false;
            $data[$v->getId() . ""] = $v;
        }

        return $data;
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

        $godmodevalue = 0;

        $driverExpiry = $this->isdriver ? $this->driverexpiry : null;

        if($this->isNew)
        { // insert
            $statement = $gDatabase->prepare("INSERT INTO user VALUES (null, :username, :password, :fullName, :experience, :medical, :emergcontact, :emergcontactphone, :mobile, :email, :emailconfirmation, :godmode, :isdriver, :profilereview, :driverexpiry, 0);");
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
            $statement->bindParam(":godmode", $godmodevalue); // force to zero - we don't
                                //want godmode users created without good reason.
            $statement->bindParam(":isdriver", $this->isdriver);
            $statement->bindParam(":profilereview", $this->profilereview);

            $statement->bindParam(":driverexpiry", $driverExpiry);

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
            $statement = $gDatabase->prepare("UPDATE user SET username = :username, password = :password, fullName = :fullName, experience = :experience, medical = :medical, emergcontact = :emergcontact, emergcontactphone = :emergcontactphone, mobile = :mobile, email = :email, emailconfirmation = :emailconfirmation, isdriver = :isdriver, profilereview = :profilereview, driverexpiry = :driverexpiry, passwordreset = :passwordreset WHERE id = :id LIMIT 1;");
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
            $statement->bindParam(":isdriver", $this->isdriver);
            $statement->bindParam(":driverexpiry", $this->driverexpiry);
            $statement->bindParam(":profilereview", $this->profilereview);
            $statement->bindParam(":passwordreset", $this->passwordreset);
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
        // does this require the user to be logged in?
        if( $action == "user" ) {
            return true;
        }

        // never allow this user right.
        if( $action == "x-denyall" ) {
            return false;
        }

        // always allow this userright
        if( $action == "public" ) {
            return true;
        }

        // OK, we've got through the basic checks. Am I god?
        if( $this->isGod() ) {
            return true;
        }

        // check the per-session rights
        if( in_array( $action, Session::getSessionRights() ) ) {
            return true;
        }

        // deny access to users who need to reset their password
        if( $this->getPasswordReset() ) {
            return false;
        }

        // Finally, check the normal user rights.
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

    public function getIsDriver()
    {
        return $this->isdriver;
    }

    public function isDriver()
    {
        return $this->isdriver;
    }

    public function setIsDriver($isdriver)
    {
        $this->isdriver = $isdriver;
    }

    public function getDriverExpiry()
    {
        global $cDisplayDateFormat;
        return $this->driverexpiry == null ? null : DateTime::createFromFormat("Y-m-d", $this->driverexpiry)->format($cDisplayDateFormat);
    }

    public function setDriverExpiry($driverexpiry)
    {
        global $cDisplayDateFormat;
        $expiry = DateTime::createFromFormat($cDisplayDateFormat, $driverexpiry);

        $this->driverexpiry = $expiry == false ? null : $expiry->format("Y-m-d");
    }

    public function getProfileReview()
    {
        return $this->profilereview;
    }

    public function setProfileReview($value)
    {
        $this->profilereview = $value;
    }

    public function getPasswordReset()
    {
        return $this->passwordreset;
    }

    public function setPasswordReset($value)
    {
        $this->passwordreset = $value;
    }

    public static function addMenuItems( $menu )
    {
        $menu = $menu[0];
        $user = User::getLoggedIn();

        global $gLogger;

        foreach( PageBase::getRegisteredPages() as $pc ) {
            $page = new $pc();
            $pagename = preg_replace( "/^Page(.*)$/", "\${1}", $pc );
            $gLogger->log("Menu generator: Using URL name of page class $pc as $pagename");

            if( ( ! $page->isProtected() ) || ( $user->isAllowed( $page->getAccessName() ) ) ) {

                $group = strtolower($page->getMenuGroup());
                $groupkey = "menu-" . strtolower( $group );

                if( ! isset( $menu[ strtolower($group) ] ) ) {
                    $menugroup = MenuGroup::getBySlug( $group );
                    if($menugroup == null) {
                        $menu[ strtolower($group) ] = array(
                            "items" => array(),
                            "title" => strtolower($group),
                            "displayname" => Message::getMessage( $groupkey )
                        );
                    }
                    else {
                        $menu[ strtolower($group) ] = array(
                            "items" => array(),
                            "title" => strtolower($menugroup->getSlug()),
                            "displayname" => $menugroup->getDisplayName()
                        );
                    }
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

    public function getGroups() {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT g.*, 'false' as isNew FROM usergroup ug INNER JOIN `group` g ON g.id = ug.`group` WHERE ug.user = :id;");
        $statement->bindParam(":id", $this->id);

        $statement->execute();

        $resultObject = $statement->fetchAll( PDO::FETCH_CLASS, "Group" );

        return $resultObject;
    }

    public function inGroup( $group ) {
        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT g.*, 'false' as isNew FROM usergroup ug INNER JOIN `group` g ON g.id = ug.`group` WHERE ug.user = :id AND ug.group = :group;");
        $statement->bindParam(":id", $this->id);
        $groupid = $group->getId();
        $statement->bindParam(":group", $groupid );

        $statement->execute();

        $resultObject = $statement->fetchAll( PDO::FETCH_CLASS, "Group" );

        return count( $resultObject ) == 1;
    }

    public function leaveGroup( $group ) {
        global $gDatabase;
        $statement = $gDatabase->prepare("DELETE FROM usergroup WHERE `user` = :id AND `group` = :group LIMIT 1;");
        $statement->bindParam(":id", $this->id);
        $groupid = $group->getId();
        $statement->bindParam(":group", $groupid );

        $statement->execute();
    }

    public function clearGroups() {
        $groups = $this->getGroups();
        $currentuser = User::getLoggedIn();
        foreach( $groups as $group ) {
            if( $group->isManager( $currentuser ) ) {
                $this->leaveGroup( $group );
            }
        }

    }

    public function getRights() {
        if( $this->isGod() )
        {
            return Right::getAllRegisteredRights();
        }

        global $gDatabase;
        $statement = $gDatabase->prepare("SELECT DISTINCT rightgroup.right FROM rightgroup INNER JOIN `group` ON `group`.id = rightgroup.`group` INNER JOIN usergroup ON `group`.id = usergroup.`group` WHERE usergroup.user = :id;");
        $statement->bindParam(":id", $this->id);

        $statement->execute();

        $resultObject = $statement->fetchAll( PDO::FETCH_COLUMN, 0 );

        return $resultObject;
    }

    public function sendPasswordReset() {
        // build a state salt for the random bytes
        $state = sha1 ( md5( uniqid( getmypid() . gethostname() . mt_rand() . microtime() . memory_get_usage(), true ) ) . mt_rand() );
        $cstrong = false;
        // generate some cool randomness
        $bytes = openssl_random_pseudo_bytes(160, $cstrong);
        $hex   = bin2hex($bytes);
        // build a password
        $pass = ( substr( base_convert( sha1( $state . $bytes ), 16, 32 ) , 0, 11 ) );

        // get mail from the DB
        $email = Message::getMessage( "forgotpassword-email" );
        $emailsubj = Message::getMessage( "forgotpassword-email-subject" );
        // subst in the password
        $email = str_replace( '$1', $pass, $email );

        Mail::send( $this->getEmail() , $emailsubj, $email );

        $this->setPassword( $pass );
        $this->save();
    }

    public function isAnonymous()
    {
        return false;
    }

    public function getGravatarHash()
    {
        return self::getGravatarHashForEmail($this->email);
    }

    public static function getGravatarHashForEmail($email)
    {
        return md5(strtolower(trim($email)));
    }
}
