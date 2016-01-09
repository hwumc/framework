<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class MenuGroup extends DataObject
{
    protected $slug;
    protected $displayname;
	protected $issecondary;
	protected $priority;

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
        else
        {
            $resultObject = new MenuGroup();
            $resultObject->setSlug( $slug );
            $resultObject->isNew = true;
            $resultObject->setIsSecondary(1);
            $resultObject->setPriority(10);
        }

        return $resultObject;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        // can't change away from this.
        if($this->slug == "main") {
            return;
        }

        $this->slug = $slug;
    }

	/**
	 * @return int
	 */
	public function getIsSecondary() {
		return $this->issecondary;
	}

	/**
	 * @param int $isSecondary 
	 */
	public function setIsSecondary($isSecondary) {
		$this->issecondary = $isSecondary;
	}

	public function getPriority() {
		return $this->priority;
	}

	public function setPriority($priority) {
		$this->priority = $priority;
	}

    public function getDisplayName(){
        if($this->displayname == null)
        {
            return $this->getSlug();
        }

        return $this->displayname;
    }

    public function setDisplayName($displayName) {
        $this->displayname = $displayName;
    }

    public function save()
    {
        global $gDatabase;

        if($this->isNew)
        { // insert
            $statement = $gDatabase->prepare("INSERT INTO menugroup (slug, displayname, issecondary, priority) VALUES (:slug, :displayname, :issecondary, :priority);");
            $statement->bindParam(":slug", $this->slug);
            $statement->bindParam(":displayname", $this->displayname);
            $statement->bindParam(":issecondary", $this->issecondary);
            $statement->bindParam(":priority", $this->priority);

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
            $statement = $gDatabase->prepare("UPDATE `menugroup` SET slug = :slug, displayname = :displayname, issecondary = :issecondary, priority = :priority WHERE id = :id LIMIT 1;");
            $statement->bindParam(":id", $this->id);
            $statement->bindParam(":slug", $this->slug);
            $statement->bindParam(":displayname", $this->displayname);
            $statement->bindParam(":issecondary", $this->issecondary);
            $statement->bindParam(":priority", $this->priority);

            if(!$statement->execute())
            {
                throw new SaveFailedException();
            }
        }
    }

    public function canDelete()
    {
        // can't delete main
        if($this->slug == "main") {
            return false;
        }

        return parent::canDelete();
    }

    public static function addMenuItems( $menu ) {
        $menu = $menu[0];

        foreach( MenuGroup::getArray() as $group ) {
            if( ! isset( $menu[ strtolower($group->getSlug()) ] ) ) {
                $menu[ strtolower($group->getSlug()) ] = array(
                    "items" => array(),
                    "title" => strtolower($group->getSlug()),
                    "displayname" => $group->getDisplayName(),
					"issecondary" => $group->getIsSecondary(),
					"priority" => $group->getPriority()
                );
            }
        }

        return $menu;
    }

}
