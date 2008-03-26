<?php
class SiteVars {
	const SIT_OBJID = "objId";
	const SIT_NAME = "Name";
	const SIT_STAFF = "Staff";
}

class objSite {

	const _TYPE = "Site";
	private $XPress;
	public $objId = '';
	public $Name = '';
	public $Staff = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Site<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		if ($objId == 0) { return false; /* must not be zero */ }
		$this->objId = $objId;
		$q = "SELECT * FROM `Site` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->Name = $result['Name'];
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
		$this->Name = '';
		$this->Staff = array();
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getName() {
		 return $this->Name;
	}
	public function getStaff() {
		 return $this->Staff;
	}

	// Mutator Functions 
	public function setObjId($value,$bSave = true) {
		$this->objId = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setName($value,$bSave = true) {
		$this->Name = $value;
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
		// Inflate "Staff":
		$q = "SELECT PersonID AS objId FROM xr_Person_Site WHERE SiteID = {$this->objId} AND SiteVar = \"Staff\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objPerson($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Staff[] = $obj;
			}
			$rcount++;
		}
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Site` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->Name.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Site`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Site` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`Name`=\"$this->Name\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(SiteVars::SIT_STAFF);
		//Delete this object's child objects

		//Delete object from the database
		$q = "DELETE FROM `Site` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Site";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Staff":
				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE SiteID=$this->objId AND PersonID=$remoteID ";
				$q0 = "INSERT INTO xr_Person_Site (SiteID,PersonID,SiteVar".(($remoteVar == '')? '' : ',PersonVar').") VALUES($this->objId,$remoteID,\"Staff\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Person_Site SET SiteVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PersonVar="{$remoteVar}" ')." WHERE SiteID=$this->objId AND PersonID=$remoteID LIMIT 1 ";
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
			case "Staff":
				$q = "DELETE FROM xr_Person_Site WHERE SiteID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PersonID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND SiteVar = \"Staff\" ";
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
			case "Staff":
				SiteXref::deleteByIDs($this->ID,"Person",$objectID,"Staff");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voSite();
		$vo->objId = $this->objId;
		$vo->Name = $this->Name;
		return $vo;
	}
	public function applyVO($voSite) {
		if(!empty($voSite->objId)){
			$this->objId = $voSite->objId;
		}
		if(!empty($voSite->Name)){
			$this->Name = $voSite->Name;
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