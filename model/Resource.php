<?php
class ResourceVars {
	const RES_OBJID = "objId";
	const RES_NAME = "Name";
	const RES_URL = "URL";
	const RES_BIOMARKERS = "Biomarkers";
	const RES_BIOMARKERORGANS = "BiomarkerOrgans";
	const RES_BIOMARKERORGANSTUDIES = "BiomarkerOrganStudies";
	const RES_BIOMARKERSTUDIES = "BiomarkerStudies";
	const RES_STUDIES = "Studies";
}

class objResource {

	const _TYPE = "Resource";
	private $XPress;
	public $objId = '';
	public $Name = '';
	public $URL = '';
	public $Biomarkers = array();
	public $BiomarkerOrgans = array();
	public $BiomarkerOrganStudies = array();
	public $BiomarkerStudies = array();
	public $Studies = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Resource<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		if ($objId == 0) { return false; /* must not be zero */ }
		$this->objId = $objId;
		$q = "SELECT * FROM `Resource` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->Name = $result['Name'];
			$this->URL = $result['URL'];
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
		$this->URL = '';
		$this->Biomarkers = array();
		$this->BiomarkerOrgans = array();
		$this->BiomarkerOrganStudies = array();
		$this->BiomarkerStudies = array();
		$this->Studies = array();
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getName() {
		 return $this->Name;
	}
	public function getURL() {
		 return $this->URL;
	}
	public function getBiomarkers() {
		 return $this->Biomarkers;
	}
	public function getBiomarkerOrgans() {
		 return $this->BiomarkerOrgans;
	}
	public function getBiomarkerOrganStudies() {
		 return $this->BiomarkerOrganStudies;
	}
	public function getBiomarkerStudies() {
		 return $this->BiomarkerStudies;
	}
	public function getStudies() {
		 return $this->Studies;
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
	public function setURL($value,$bSave = true) {
		$this->URL = $value;
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
		// Inflate "Biomarkers":
		$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"Biomarkers\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarker($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Biomarkers[] = $obj;
			}
			$rcount++;
		}
		// Inflate "BiomarkerStudies":
		$q = "SELECT BiomarkerStudyDataID AS objId FROM xr_BiomarkerStudyData_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"BiomarkerStudies\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerStudyData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->BiomarkerStudies[] = $obj;
			}
			$rcount++;
		}
		// Inflate "BiomarkerOrgans":
		$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"BiomarkerOrgans\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerOrganData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->BiomarkerOrgans[] = $obj;
			}
			$rcount++;
		}
		// Inflate "BiomarkerOrganStudies":
		$q = "SELECT BiomarkerOrganStudyDataID AS objId FROM xr_BiomarkerOrganStudyData_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"BiomarkerOrganStudies\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerOrganStudyData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->BiomarkerOrganStudies[] = $obj;
			}
			$rcount++;
		}
		// Inflate "Studies":
		$q = "SELECT StudyID AS objId FROM xr_Study_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"Studies\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objStudy($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Studies[] = $obj;
			}
			$rcount++;
		}
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Resource` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->Name.'","'.$this->URL.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Resource`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Resource` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`Name`=\"$this->Name\","; 
			$q .= "`URL`=\"$this->URL\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(ResourceVars::RES_BIOMARKERS);
		$this->unlink(ResourceVars::RES_BIOMARKERORGANS);
		$this->unlink(ResourceVars::RES_BIOMARKERORGANSTUDIES);
		$this->unlink(ResourceVars::RES_BIOMARKERSTUDIES);
		$this->unlink(ResourceVars::RES_STUDIES);
		//Delete this object's child objects

		//Delete object from the database
		$q = "DELETE FROM `Resource` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Resource";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarkers":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Resource WHERE ResourceID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_Resource (ResourceID,BiomarkerID,ResourceVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarkers\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerStudies":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Resource WHERE ResourceID=$this->objId AND BiomarkerStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Resource (ResourceID,BiomarkerStudyDataID,ResourceVar".(($remoteVar == '')? '' : ',BiomarkerStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerStudies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerStudyDataVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrgans":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Resource WHERE ResourceID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Resource (ResourceID,BiomarkerOrganDataID,ResourceVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrgans\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrganStudies":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Resource WHERE ResourceID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Resource (ResourceID,BiomarkerOrganStudyDataID,ResourceVar".(($remoteVar == '')? '' : ',BiomarkerOrganStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrganStudies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganStudyDataVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "Studies":
				$q  = "SELECT COUNT(*) FROM xr_Study_Resource WHERE ResourceID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_Resource (ResourceID,StudyID,ResourceVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Studies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
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
			case "Biomarkers":
				$q = "DELETE FROM xr_Biomarker_Resource WHERE ResourceID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND ResourceVar = \"Biomarkers\" ";
				break;
			case "BiomarkerStudies":
				$q = "DELETE FROM xr_BiomarkerStudyData_Resource WHERE ResourceID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND ResourceVar = \"BiomarkerStudies\" ";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM xr_BiomarkerOrganData_Resource WHERE ResourceID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND ResourceVar = \"BiomarkerOrgans\" ";
				break;
			case "BiomarkerOrganStudies":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Resource WHERE ResourceID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND ResourceVar = \"BiomarkerOrganStudies\" ";
				break;
			case "Studies":
				$q = "DELETE FROM xr_Study_Resource WHERE ResourceID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND StudyID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND ResourceVar = \"Studies\" ";
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
			case "Biomarkers":
				ResourceXref::deleteByIDs($this->ID,"Biomarker",$objectID,"Biomarkers");
				break;
			case "BiomarkerOrgans":
				ResourceXref::deleteByIDs($this->ID,"BiomarkerOrganData",$objectID,"BiomarkerOrgans");
				break;
			case "BiomarkerOrganStudies":
				ResourceXref::deleteByIDs($this->ID,"BiomarkerOrganStudyData",$objectID,"BiomarkerOrganStudies");
				break;
			case "BiomarkerStudies":
				ResourceXref::deleteByIDs($this->ID,"BiomarkerStudyData",$objectID,"BiomarkerStudies");
				break;
			case "Studies":
				ResourceXref::deleteByIDs($this->ID,"Study",$objectID,"Studies");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voResource();
		$vo->objId = $this->objId;
		$vo->Name = $this->Name;
		$vo->URL = $this->URL;
		return $vo;
	}
	public function applyVO($voResource) {
		if(!empty($voResource->objId)){
			$this->objId = $voResource->objId;
		}
		if(!empty($voResource->Name)){
			$this->Name = $voResource->Name;
		}
		if(!empty($voResource->URL)){
			$this->URL = $voResource->URL;
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