<?php
class OrganVars {
	const ORG_OBJID = "objId";
	const ORG_NAME = "Name";
	const ORG_ORGANDATAS = "OrganDatas";
}

class objOrgan {

	const _TYPE = "Organ";
	private $XPress;
	public $objId = '';
	public $Name = '';
	public $OrganDatas = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Organ<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		if ($objId == 0) { return false; /* must not be zero */ }
		$this->objId = $objId;
		$q = "SELECT * FROM `Organ` WHERE objId={$this->objId} LIMIT 1";
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
	public function initializeByUniqueKey($key,$value,$inflate = true,$parentObjects = array()) {
		switch ($key) {
			case "Name":
				$this->Name = $value;
				$q = "SELECT * FROM `Organ` WHERE `Name`=\"{$value}\" LIMIT 1";
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
				break;
			default:
				break;
		}
	}
	public function deflate(){
		// reset all member variables to initial settings
		$this->objId = '';
		$this->Name = '';
		$this->OrganDatas = array();
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getName() {
		 return $this->Name;
	}
	public function getOrganDatas() {
		 return $this->OrganDatas;
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
	public function create($Name){
		$this->Name = $Name;
		$this->save();
	}
	public function inflate($parentObjects = array()) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "OrganDatas":
		$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_Organ WHERE OrganID = {$this->objId} AND OrganVar = \"OrganDatas\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerOrganData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->OrganDatas[] = $obj;
			}
			$rcount++;
		}
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Organ` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->Name.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Organ`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Organ` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`Name`=\"$this->Name\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(OrganVars::ORG_ORGANDATAS);
		//Delete this object's child objects
		foreach ($this->OrganDatas as $obj){
			$obj->delete();
		}

		//Delete object from the database
		$q = "DELETE FROM `Organ` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Organ";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "OrganDatas":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Organ WHERE OrganID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Organ (OrganID,BiomarkerOrganDataID,OrganVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"OrganDatas\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Organ SET OrganVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE OrganID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
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
			case "OrganDatas":
				$q = "DELETE FROM xr_BiomarkerOrganData_Organ WHERE OrganID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND OrganVar = \"OrganDatas\" ";
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
			case "OrganDatas":
				OrganXref::deleteByIDs($this->ID,"BiomarkerOrganData",$objectID,"OrganDatas");
				BiomarkerOrganData::Delete($objectID);
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voOrgan();
		$vo->objId = $this->objId;
		$vo->Name = $this->Name;
		return $vo;
	}
	public function applyVO($voOrgan) {
		if(!empty($voOrgan->objId)){
			$this->objId = $voOrgan->objId;
		}
		if(!empty($voOrgan->Name)){
			$this->Name = $voOrgan->Name;
		}
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Organ rdf:about=\"{$urlBase}/editors/showOrgan.php?o={$this->ID}\">\r\n<{$namespace}:objId>$this->objId</{$namespace}:objId>\r\n<{$namespace}:Name>$this->Name</{$namespace}:Name>\r\n";
		foreach ($this->OrganDatas as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:Organ>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Organ rdf:about=\"{$urlBase}/editors/showOrgan.php?o={$this->ID}\"/>\r\n";
		return $rdf;
	}
}

?>