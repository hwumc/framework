<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

/**
 * DataObject is the base class for all the database access classes. Each "DataObject" holds one record from the database, and
 * provides functions to allow loading from and saving to the database.
 *
 *
 */
abstract class DataObject
{
	protected $id = 0;

	protected $isNew = true;

    public static function getIdList()
	{
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT id FROM `" . strtolower( get_called_class() ) . "`;");
		$statement->execute();

		$result = $statement->fetchAll( PDO::FETCH_COLUMN, 0 );

		return $result;
	}
    
	public static function getArray() {
		$output = array();
		$input = self::getIdList();
		
		foreach( $input as $g ) {
			$output[ $g ] = self::getById( $g );
		}
		
		return $output;
	}
    
	/**
	 * Retrieves a data object by it's row ID.
	 */
	public static function getById($id) {
		global $gDatabase;
		$statement = $gDatabase->prepare("SELECT * FROM `" . strtolower( get_called_class() ) . "` WHERE id = :id LIMIT 1;");
		$statement->bindParam(":id", $id);

		$statement->execute();

		$resultObject = $statement->fetchObject( get_called_class() );

		if($resultObject != false)
		{
			$resultObject->isNew = false;
		}

		return $resultObject;
	}

	/**
	 * Saves a data object to the database, either updating or inserting a record.
	 */
	public abstract function save();

	public function getId()
	{
		return $this->id;
	}

	public function delete() 
    {
		global $gDatabase;
		$statement = $gDatabase->prepare("DELETE FROM `" . strtolower( get_called_class() ) . "` WHERE id = :id LIMIT 1;");
		$statement->bindParam(":id", $this->id);
		$statement->execute();

		$this->id=0;
		$this->isNew = true;
    }
    
    public function canDelete()
    {
        global $gDatabase;
        if($gDatabase->beginTransaction())
        {
            $success = false;
            
            try
            {
                $this->delete();
                $success = true;
            }    
            catch (PDOException $ex)
            {
                $success = false;   
            }
            
            $gDatabase->rollBack();
            
            return $success;
        }
        else return false;
    }
}
