<?php


class PersonVars {
	const OBJID = "objId";
	const FIRSTNAME = "FirstName";
	const LASTNAME = "LastName";
	const TITLE = "Title";
	const SPECIALTY = "Specialty";
	const PHONE = "Phone";
	const FAX = "Fax";
	const EMAIL = "Email";
	const SITE = "Site";
}

class PersonFactory {
	public static function Create(){
		$o = new Person();
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = PersonVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Person();
		switch ($key) {
			case PersonVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Person` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setFirstName($data['FirstName'],false);
				$o->setLastName($data['LastName'],false);
				$o->setTitle($data['Title'],false);
				$o->setSpecialty($data['Specialty'],false);
				$o->setPhone($data['Phone'],false);
				$o->setFax($data['Fax'],false);
				$o->setEmail($data['Email'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Person extends XPressObject {

	const _TYPE = "Person";
	public $FirstName = '';
	public $LastName = '';
	public $Title = '';
	public $Specialty = '';
	public $Phone = '';
	public $Fax = '';
	public $Email = '';
	public $Site = array();


	public function __construct($objId = 0) {
		//echo "creating object of type Person<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getFirstName() {
		 return $this->FirstName;
	}
	public function getLastName() {
		 return $this->LastName;
	}
	public function getTitle() {
		 return $this->Title;
	}
	public function getSpecialty() {
		 return $this->Specialty;
	}
	public function getPhone() {
		 return $this->Phone;
	}
	public function getFax() {
		 return $this->Fax;
	}
	public function getEmail() {
		 return $this->Email;
	}
	public function getSite() {
		if ($this->Site != array()) {
			return $this->Site;
		} else {
			$this->inflate(PersonVars::SITE);
			return $this->Site;
		}
	}

	// Mutator Functions 
	public function setFirstName($value,$bSave = true) {
		$this->FirstName = $value;
		if ($bSave){
			$this->save(PersonVars::FIRSTNAME);
		}
	}
	public function setLastName($value,$bSave = true) {
		$this->LastName = $value;
		if ($bSave){
			$this->save(PersonVars::LASTNAME);
		}
	}
	public function setTitle($value,$bSave = true) {
		$this->Title = $value;
		if ($bSave){
			$this->save(PersonVars::TITLE);
		}
	}
	public function setSpecialty($value,$bSave = true) {
		$this->Specialty = $value;
		if ($bSave){
			$this->save(PersonVars::SPECIALTY);
		}
	}
	public function setPhone($value,$bSave = true) {
		$this->Phone = $value;
		if ($bSave){
			$this->save(PersonVars::PHONE);
		}
	}
	public function setFax($value,$bSave = true) {
		$this->Fax = $value;
		if ($bSave){
			$this->save(PersonVars::FAX);
		}
	}
	public function setEmail($value,$bSave = true) {
		$this->Email = $value;
		if ($bSave){
			$this->save(PersonVars::EMAIL);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Site":
				// Inflate "Site":
				$q = "SELECT SiteID AS objId FROM xr_Person_Site WHERE PersonID = {$this->objId} AND PersonVar = \"Site\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Site = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Site[] = SiteFactory::retrieve($id[objId]);
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
		$this->FirstName = '';
		$this->LastName = '';
		$this->Title = '';
		$this->Specialty = '';
		$this->Phone = '';
		$this->Fax = '';
		$this->Email = '';
		$this->Site = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Person` ";
			$q .= 'VALUES("","'.$this->FirstName.'","'.$this->LastName.'","'.$this->Title.'","'.$this->Specialty.'","'.$this->Phone.'","'.$this->Fax.'","'.$this->Email.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Person`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Person` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Person` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`FirstName`=\"{$this->FirstName}\","; 
				$q .= "`LastName`=\"{$this->LastName}\","; 
				$q .= "`Title`=\"{$this->Title}\","; 
				$q .= "`Specialty`=\"{$this->Specialty}\","; 
				$q .= "`Phone`=\"{$this->Phone}\","; 
				$q .= "`Fax`=\"{$this->Fax}\","; 
				$q .= "`Email`=\"{$this->Email}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(PersonVars::SITE);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Person_Site WHERE `PersonID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Person WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Person::_TYPE; //Person
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Site":
				$test = "SELECT COUNT(*) FROM Site WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE PersonID=$this->objId AND SiteID=$remoteID ";
				$q0 = "INSERT INTO xr_Person_Site (PersonID,SiteID,PersonVar".(($remoteVar == '')? '' : ',SiteVar').") VALUES($this->objId,$remoteID,\"Site\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Person_Site SET PersonVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', SiteVar="{$remoteVar}" ')." WHERE PersonID=$this->objId AND SiteID=$remoteID LIMIT 1 ";
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
			case "Site":
				$q = "DELETE FROM xr_Person_Site WHERE PersonID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND SiteID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PersonVar = \"Site\" ";
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