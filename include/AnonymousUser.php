<?php

// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");
class AnonymousUser extends User
{
	public function save()
	{
	}

	public function getUsername()
	{
		return "anonymous";
	}

	public function setPassword($password)
	{
	}

	public function setUsername($username)
	{
	}
	
	public function getFullName()
	{
		return "Anonymous Coward";
	}

	public function setFullName($fullName)
	{
	}

	public function getExperience()
	{
		return "";
	}

	public function setExperience($experience)
	{
	}

	public function getMedical()
	{
		return "";
	}

	public function setMedical($medical)
	{
	}

	public function getEmergencyContact()
	{
		return "";
	}

	public function setEmergencyContact($emergcontact)
	{
	}
	public function getEmergencyContactPhone()
	{
		return "";
	}

	public function setEmergencyContactPhone($emergcontact)
	{
	}

	public function getMobile()
	{
		return "";
	}

	public function setMobile($mobile)
	{
	}

	public function getEmail()
	{
		return "";
	}

	public function setEmail($email)
	{
	}

	public function isAllowed($action)
	{
        return $action == "public";
	}
    
	public function isMailConfirmed()
	{
		return false;
	}	
	
	public function isGod()
	{
		return false;
	}
	
	public function getGroups() {
		return array();
	}
    
    public function inGroup( $group ) {
        return false;
    }
    
    public function leaveGroup( $group ) {
    }
	
	public function clearGroups() {
	}
	
	public function getRights() {
		return array("public");
	}
	
	public function sendPasswordReset() {
		throw new YouShouldntBeDoingThatException();
	}

    public function isAnonymous()
    {
        return true;   
    }
}
