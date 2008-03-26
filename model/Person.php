<?php
class PersonVars {
	const PER_OBJID = "objId";
	const PER_FIRSTNAME = "FirstName";
	const PER_LASTNAME = "LastName";
	const PER_TITLE = "Title";
	const PER_SPECIALTY = "Specialty";
	const PER_PHONE = "Phone";
	const PER_FAX = "Fax";
	const PER_EMAIL = "Email";
	const PER_SITE = "Site";
}

class objPerson {

	const _TYPE = "Person";
	private $XPress;
	public $objId = '';
	public $FirstName = '';
	public $LastName = '';
	public $Title = '';
	public $Specialty = '';
	public $Phone = '';
	public $Fax = '';
	public $Email = '';
	public $Site = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Person<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		if ($objId == 0) { return false; /* must not be zero */ }
		$this->objId = $objId;
		$q = "SELECT * FROM `Person` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->FirstName = $result['FirstName'];
			$this->LastName = $result['LastName'];
			$this->Title = $result['Title'];
			$this->Specialty = $result['Specialty'];
			$this->Phone = $result['Phone'];
			$this->Fax = $result['Fax'];
			$this->Email = $result['Email'];
		}
		if ($inflate){
			return $this->inflate($parentObjects);
		} else {
			return true;
		}
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
		 return $this->Site;
	}

	// Mutator Functions 
	public function setObjId($value,$bSave = true) {
		$this->objId = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setFirstName($value,$bSave = true) {
		$this->FirstName = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setLastName($value,$bSave = true) {
		$this->LastName = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setTitle($value,$bSave = true) {
		$this->Title = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSpecialty($value,$bSave = true) {
		$this->Specialty = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setPhone($value,$bSave = true) {
		$this->Phone = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setFax($value,$bSave = true) {
		$this->Fax = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setEmail($value,$bSave = true) {
		$this->Email = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function create(){
		$this->save();
	}
	public function inflate($parentObjects = array()) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "Site":
		$q = "SELECT SiteID AS objId FROM xr_Person_Site WHERE PersonID = {$this->objId} AND PersonVar = \"Site\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objSite($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Site[] = $obj;
			}
			$rcount++;
		}
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Person` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->FirstName.'","'.$this->LastName.'","'.$this->Title.'","'.$this->Specialty.'","'.$this->Phone.'","'.$this->Fax.'","'.$this->Email.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Person`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Person` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`FirstName`=\"$this->FirstName\","; 
			$q .= "`LastName`=\"$this->LastName\","; 
			$q .= "`Title`=\"$this->Title\","; 
			$q .= "`Specialty`=\"$this->Specialty\","; 
			$q .= "`Phone`=\"$this->Phone\","; 
			$q .= "`Fax`=\"$this->Fax\","; 
			$q .= "`Email`=\"$this->Email\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(PersonVars::PER_SITE);
		//Delete this object's child objects

		//Delete object from the database
		$q = "DELETE FROM `Person` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Person";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Site":
				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE PersonID=$this->objId AND SiteID=$remoteID ";
				$q0 = "INSERT INTO xr_Person_Site (PersonID,SiteID,PersonVar".(($remoteVar == '')? '' : ',SiteVar').") VALUES($this->objId,$remoteID,\"Site\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Person_Site SET PersonVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', SiteVar="{$remoteVar}" ')." WHERE PersonID=$this->objId AND SiteID=$remoteID LIMIT 1 ";
				break;
			default:
				break;
		}
		$count  = $this->XPress->Database->safeGetOne($q);
		if ($count == 0){
			$this->XPress->Database->safeQuery($q0);
		} else {
			$this->XPress->Database->safeQuery($q1);
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
		$r  = $this->XPress->Database->safeQuery($q);
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
	public function dissociate($objectID,$variableName) {
		switch ($variableName) {
			case "Site":
				PersonXref::deleteByIDs($this->ID,"Site",$objectID,"Site");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voPerson();
		$vo->objId = $this->objId;
		$vo->FirstName = $this->FirstName;
		$vo->LastName = $this->LastName;
		$vo->Title = $this->Title;
		$vo->Specialty = $this->Specialty;
		$vo->Phone = $this->Phone;
		$vo->Fax = $this->Fax;
		$vo->Email = $this->Email;
		return $vo;
	}
	public function applyVO($voPerson) {
		if(!empty($voPerson->objId)){
			$this->objId = $voPerson->objId;
		}
		if(!empty($voPerson->FirstName)){
			$this->FirstName = $voPerson->FirstName;
		}
		if(!empty($voPerson->LastName)){
			$this->LastName = $voPerson->LastName;
		}
		if(!empty($voPerson->Title)){
			$this->Title = $voPerson->Title;
		}
		if(!empty($voPerson->Specialty)){
			$this->Specialty = $voPerson->Specialty;
		}
		if(!empty($voPerson->Phone)){
			$this->Phone = $voPerson->Phone;
		}
		if(!empty($voPerson->Fax)){
			$this->Fax = $voPerson->Fax;
		}
		if(!empty($voPerson->Email)){
			$this->Email = $voPerson->Email;
		}
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}
}

?>