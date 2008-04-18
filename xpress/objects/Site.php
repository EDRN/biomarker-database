<?php


class SiteVars {
	const OBJID = "objId";
	const NAME = "Name";
	const STAFF = "Staff";
}

class SiteFactory {
	public static function Create(){
		$o = new Site();
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = SiteVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Site();
		switch ($key) {
			case SiteVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Site` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setName($data['Name'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Site extends XPressObject {

	const _TYPE = "Site";
	public $Name = '';
	public $Staff = array();


	public function __construct($objId = 0) {
		//echo "creating object of type Site<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getName() {
		 return $this->Name;
	}
	public function getStaff() {
		if ($this->Staff != array()) {
			return $this->Staff;
		} else {
			$this->inflate(SiteVars::STAFF);
			return $this->Staff;
		}
	}

	// Mutator Functions 
	public function setName($value,$bSave = true) {
		$this->Name = $value;
		if ($bSave){
			$this->save(SiteVars::NAME);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Staff":
				// Inflate "Staff":
				$q = "SELECT PersonID AS objId FROM xr_Person_Site WHERE SiteID = {$this->objId} AND SiteVar = \"Staff\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Staff = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Staff[] = PersonFactory::retrieve($id[objId]);
				}
				break;
			default:
				return false;
		}
		return true;
	}
	public function deflate(){
		// reset all member variables to initial settings
		$this->objId = '';
		$this->Name = '';
		$this->Staff = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Site` ";
			$q .= 'VALUES("","'.$this->Name.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Site`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Site` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Site` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`Name`=\"{$this->Name}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(SiteVars::STAFF);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Person_Site WHERE `SiteID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Site WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Site::_TYPE; //Site
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Staff":
				$test = "SELECT COUNT(*) FROM Person WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE SiteID=$this->objId AND PersonID=$remoteID ";
				$q0 = "INSERT INTO xr_Person_Site (SiteID,PersonID,SiteVar".(($remoteVar == '')? '' : ',PersonVar').") VALUES($this->objId,$remoteID,\"Staff\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Person_Site SET SiteVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PersonVar="{$remoteVar}" ')." WHERE SiteID=$this->objId AND PersonID=$remoteID LIMIT 1 ";
				break;
			default:
				break;
		}
		if (1 != XPress::getInstance()->getDatabase()->getOne($test)) {
			return false; // The requested remote id does not exist!
		}
		$count  = XPress::getInstance()->getDatabase()->getOne($q);
		if ($count == 0){
			XPress::getInstance()->getDatabase()->query($q0);
		} else {
			XPress::getInstance()->getDatabase()->query($q1);
		}
		return true;
	}
	public function unlink($variable,$remoteIDs = ''){
		switch ($variable){
			case "Staff":
				$q = "DELETE FROM xr_Person_Site WHERE SiteID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PersonID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND SiteVar = \"Staff\" ";
				break;
			default:
				break;
		}
		$r  = XPress::getInstance()->getDatabase()->query($q);
		return true;
	}
	public function equals($objArray){
		if ($objArray == null){return false;}
		//print_r($objArray);
		foreach ($objArray as $obj){
			//echo "::EQUALS:: comparing {$this->_getType()} WITH {$obj->_getType()} &nbsp;<br/>";
			// Check object types first
			if ($this->_getType() == $obj->_getType()){
				// Check objId next
				if ($this->objId != $obj->objId){continue;}
				return true;
			}
		}
		return false;
	}
	public function toJSON(){
		return json_encode($this);
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}

	// API Extensions 
	// -@-	// -@-
	// End API Extensions --
}

?>