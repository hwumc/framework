<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Message extends DataObject
{

	protected static $cache = array();

	protected static $requestLanguage = "";

	/**
	 * Database fields
	 */
	protected $name;
	protected $code;
	protected $content;

	/**
	 * Retrieves a list of all the known message keys
	 */
	public static function getMessageKeys()
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT DISTINCT name FROM message;");
		$statement->execute();

		$result = $statement->fetchAll(PDO::FETCH_COLUMN,0);

		return $result;
	}

	public static function getByName($name, $language)
	{
		// language pseudocode exception
		// the idea of this is you can use language code zxx
		// to view all the available language codes in situ.
		if($language == "zxx")
		{
			$m = new Message();
			$m->name = $name;
			$m->code = $language;
			$m->content = $name;

			return $m;
		}
		// end of language pseudocode exception

		// check local cache
		if( isset( self::$cache[ $language ] ) ) {
			if( isset( self::$cache[ $language ][ $name ] ) ) {
				return self::$cache[ $language ][ $name ];
			}
		} else {
			self::$cache[ $language ] = array();
		}

		$resultMessage = self::reallyGetByName( $name, $language );
		
		if($resultMessage != false)
		{
			$resultMessage->isNew = false;
			
			self::$cache[ $language ][ $name ] = $resultMessage;
			
			return $resultMessage;
		}
		else
		{
			self::$cache[ $language ][ $name ] = self::getError($name, $language);
		
			return self::$cache[ $language ][ $name ];
		}
	}
	
	private static function reallyGetByName( $name, $language ) {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM message WHERE name = :name AND code = :language LIMIT 1;");
		$statement->bindParam(":name", $name);
		$statement->bindParam(":language", $language);

		$statement->execute();

		return $statement->fetchObject("Message");
	}

	public static function retrieveContent($name, $language)
	{
		return self::getByName($name, $language)->getContent();
	}
	
	public static function getMessage($name)
	{
		$lang = self::getActiveLanguage();
	
		if($lang==false)
			throw new Exception("No active language!");
	
		return self::getByName($name, $lang)->getContent();
	}
	
	/**
	 * Returns an error version of a requested message.
	 */
	public static function getError( $name,  $language)
	{
		if(strlen($name) == 0)
			throw new Exception("Cannot create an error Message object with no name!");
        
		if(strlen($name) > 75)
			throw new FieldTooLargeException("Cannot create a Message object with a name that large!");
	
		if(strlen($language) == 0)
			throw new Exception("Cannot create an error Message object with no language!");
	
		$em = new Message();
		$em->code = $language;
		$em->name = $name;
		$em->content = "<$language:$name>";
		$em->isNew = true;

		$em->save();

		return $em;
	}
	
	public static function getActiveLanguage()
	{
		global $cAvailableLanguages;
	
		// look in the order of most volatile first - if we find something, use it.
		// request cache
		if(self::$requestLanguage != "")
		{
			return self::$requestLanguage;
		}
		
		// get parameter
		$getParam = WebRequest::get("lang");
		if($getParam != false)
		{
			// check value is in list of allowed values
			if(array_key_exists($getParam, $cAvailableLanguages))
			{
				// save local cache for other messages this request
				self::$requestLanguage = $getParam;
			
				// set a cookie to persist that option for this session (do we want
				// this option to set the preferences too?)
				WebRequest::setCookie("lang", $getParam);
			
				// use this value.
				return $getParam;
			}
		}
		
		// cookie
		$cookie = WebRequest::getCookie("lang");
		if($cookie != false)
		{
			// check value is in list of allowed values
			if(array_key_exists($cookie, $cAvailableLanguages))
			{
				// save local cache for other messages this request
				self::$requestLanguage = $cookie;
			
				// use this value.
				return $cookie;
			}
		
		}
		// user preference
		
		// site default
		global $cDefaultLanguage;
		return $cDefaultLanguage;
	}
	
	/**
	 * Never call this function.
	 *
	 * Really, don't do it.
	 *
	 * Don't delete it either.
	 *
	 * -- Simon :D xx
	 */
	public static function smartyFuncMessage( $params, $smarty ) {
		$language = self::getActiveLanguage();
		
		$name = $params["name"];
		
		return htmlentities( self::retrieveContent( $name, $language ), ENT_COMPAT , 'UTF-8' );
	}
	
	public static function clearAll( ) {
		global $gDatabase;
		$statement = $gDatabase->prepare("TRUNCATE TABLE message;");
		$statement->execute();
	}	
	public static function clearAllUnset( ) {
		global $gDatabase;
		$statement = $gDatabase->prepare('DELETE FROM message WHERE content = concat("<", code, ":", name, ">");');
		$statement->execute();
	}
    public static function clearAllKey( $key ) {
        global $gDatabase;
        $statement = $gDatabase->prepare( 'DELETE FROM message WHERE name = :key;' );
        $statement->bindParam( ":key", $key );
        $statement->execute();
    }
	
	public function getName()
	{
		return $this->name;
	}
	public function getCode()
	{
		return $this->code;
	}
	public function getContent()
	{
		return $this->content;
	}
	public function setContent($newcontent)
	{
		$this->content = $newcontent;
	}
	
	public function save()
	{
		try
		{
			if($this->isNew)
			{
				global $gDatabase;
				$statement = $gDatabase->prepare("INSERT INTO message (name, code, content) VALUES (:name, :code, :content);");

				if($this->name == "")
					throw new SaveFailedException("No name set!");
				
				if($this->code == "")
					throw new SaveFailedException("No code set!");
				
				$statement->bindParam(":name", $this->name);
				$statement->bindParam(":code", $this->code);
				$statement->bindParam(":content", $this->content);

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
			{
				global $gDatabase;
				$statement = $gDatabase->prepare("UPDATE message SET content = :content WHERE id = :id LIMIT 1;");

				$statement->bindParam(":id", $this->id);
				$statement->bindParam(":content", $this->content);

				if(! $statement->execute())
				{
					throw new SaveFailedException();
				}
			}
		}
		catch( PDOException $ex)
		{
			throw new SaveFailedException($ex->getMessage(), $ex->getCode(), $ex);
		}
	}

	public function delete()
	{
		throw new Exception("Not implemented");
	}
}
