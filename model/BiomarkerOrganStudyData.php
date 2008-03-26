<?php
class BiomarkerOrganStudyDataVars {
	const BIO_OBJID = "objId";
	const BIO_SENSITIVITY = "Sensitivity";
	const BIO_SPECIFICITY = "Specificity";
	const BIO_PPV = "PPV";
	const BIO_NPV = "NPV";
	const BIO_ASSAY = "Assay";
	const BIO_TECHNOLOGY = "Technology";
	const BIO_STUDY = "Study";
	const BIO_BIOMARKERORGANDATA = "BiomarkerOrganData";
	const BIO_PUBLICATIONS = "Publications";
	const BIO_RESOURCES = "Resources";
}

class objBiomarkerOrganStudyData {

	const _TYPE = "BiomarkerOrganStudyData";
	private $XPress;
	public $objId = '';
	public $Sensitivity = '';
	public $Specificity = '';
	public $PPV = '';
	public $NPV = '';
	public $Assay = '';
	public $Technology = '';
	public $Study = '';
	public $BiomarkerOrganData = '';
	public $Publications = array();
	public $Resources = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type BiomarkerOrganStudyData<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		if ($objId == 0) { return false; /* must not be zero */ }
		$this->objId = $objId;
		$q = "SELECT * FROM `BiomarkerOrganStudyData` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->Sensitivity = $result['Sensitivity'];
			$this->Specificity = $result['Specificity'];
			$this->PPV = $result['PPV'];
			$this->NPV = $result['NPV'];
			$this->Assay = $result['Assay'];
			$this->Technology = $result['Technology'];
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
		$this->Sensitivity = '';
		$this->Specificity = '';
		$this->PPV = '';
		$this->NPV = '';
		$this->Assay = '';
		$this->Technology = '';
		$this->Study = '';
		$this->BiomarkerOrganData = '';
		$this->Publications = array();
		$this->Resources = array();
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getSensitivity() {
		 return $this->Sensitivity;
	}
	public function getSpecificity() {
		 return $this->Specificity;
	}
	public function getPPV() {
		 return $this->PPV;
	}
	public function getNPV() {
		 return $this->NPV;
	}
	public function getAssay() {
		 return $this->Assay;
	}
	public function getTechnology() {
		 return $this->Technology;
	}
	public function getStudy() {
		 return $this->Study;
	}
	public function getBiomarkerOrganData() {
		 return $this->BiomarkerOrganData;
	}
	public function getPublications() {
		 return $this->Publications;
	}
	public function getResources() {
		 return $this->Resources;
	}

	// Mutator Functions 
	public function setObjId($value,$bSave = true) {
		$this->objId = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSensitivity($value,$bSave = true) {
		$this->Sensitivity = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSpecificity($value,$bSave = true) {
		$this->Specificity = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setPPV($value,$bSave = true) {
		$this->PPV = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setNPV($value,$bSave = true) {
		$this->NPV = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setAssay($value,$bSave = true) {
		$this->Assay = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setTechnology($value,$bSave = true) {
		$this->Technology = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function create($StudyId,$BiomarkerOrganDataId){
		$this->save();
		$this->link("Study",$StudyId,"BiomarkerOrganDatas");
		$this->link("BiomarkerOrganData",$BiomarkerOrganDataId,"StudyDatas");
	}
	public function inflate($parentObjects = array()) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "BiomarkerOrganData":
		$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"BiomarkerOrganData\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerOrganData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->BiomarkerOrganData = $obj;
			}
			$rcount++;
		}
		// Inflate "Study":
		$q = "SELECT StudyID AS objId FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"Study\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objStudy($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Study = $obj;
			}
			$rcount++;
		}
		// Inflate "Publications":
		$q = "SELECT PublicationID AS objId FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"Publications\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objPublication($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Publications[] = $obj;
			}
			$rcount++;
		}
		// Inflate "Resources":
		$q = "SELECT ResourceID AS objId FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"Resources\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objResource($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Resources[] = $obj;
			}
			$rcount++;
		}
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `BiomarkerOrganStudyData` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->Sensitivity.'","'.$this->Specificity.'","'.$this->PPV.'","'.$this->NPV.'","'.$this->Assay.'","'.$this->Technology.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `BiomarkerOrganStudyData`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `BiomarkerOrganStudyData` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`Sensitivity`=\"$this->Sensitivity\","; 
			$q .= "`Specificity`=\"$this->Specificity\","; 
			$q .= "`PPV`=\"$this->PPV\","; 
			$q .= "`NPV`=\"$this->NPV\","; 
			$q .= "`Assay`=\"$this->Assay\","; 
			$q .= "`Technology`=\"$this->Technology\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerOrganStudyDataVars::BIO_STUDY);
		$this->unlink(BiomarkerOrganStudyDataVars::BIO_BIOMARKERORGANDATA);
		$this->unlink(BiomarkerOrganStudyDataVars::BIO_PUBLICATIONS);
		$this->unlink(BiomarkerOrganStudyDataVars::BIO_RESOURCES);
		//Delete this object's child objects

		//Delete object from the database
		$q = "DELETE FROM `BiomarkerOrganStudyData` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "BiomarkerOrganStudyData";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "BiomarkerOrganData":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_BiomarkerOrganStudyData (BiomarkerOrganStudyDataID,BiomarkerOrganDataID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrganData\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_BiomarkerOrganStudyData SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Study":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Study (BiomarkerOrganStudyDataID,StudyID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Study\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Study SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Publication (BiomarkerOrganStudyDataID,PublicationID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Publication SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Resource (BiomarkerOrganStudyDataID,ResourceID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Resource SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
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
			case "BiomarkerOrganData":
				$q = "DELETE FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerOrganStudyDataVar = \"BiomarkerOrganData\" ";
				break;
			case "Study":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND StudyID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerOrganStudyDataVar = \"Study\" ";
				break;
			case "Publications":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PublicationID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerOrganStudyDataVar = \"Publications\" ";
				break;
			case "Resources":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND ResourceID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerOrganStudyDataVar = \"Resources\" ";
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
			case "Study":
				BiomarkerOrganStudyDataXref::deleteByIDs($this->ID,"Study",$objectID,"Study");
				break;
			case "BiomarkerOrganData":
				BiomarkerOrganStudyDataXref::deleteByIDs($this->ID,"BiomarkerOrganData",$objectID,"BiomarkerOrganData");
				break;
			case "Publications":
				BiomarkerOrganStudyDataXref::deleteByIDs($this->ID,"Publication",$objectID,"Publications");
				break;
			case "Resources":
				BiomarkerOrganStudyDataXref::deleteByIDs($this->ID,"Resource",$objectID,"Resources");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voBiomarkerOrganStudyData();
		$vo->objId = $this->objId;
		$vo->Sensitivity = $this->Sensitivity;
		$vo->Specificity = $this->Specificity;
		$vo->PPV = $this->PPV;
		$vo->NPV = $this->NPV;
		$vo->Assay = $this->Assay;
		$vo->Technology = $this->Technology;
		return $vo;
	}
	public function applyVO($voBiomarkerOrganStudyData) {
		if(!empty($voBiomarkerOrganStudyData->objId)){
			$this->objId = $voBiomarkerOrganStudyData->objId;
		}
		if(!empty($voBiomarkerOrganStudyData->Sensitivity)){
			$this->Sensitivity = $voBiomarkerOrganStudyData->Sensitivity;
		}
		if(!empty($voBiomarkerOrganStudyData->Specificity)){
			$this->Specificity = $voBiomarkerOrganStudyData->Specificity;
		}
		if(!empty($voBiomarkerOrganStudyData->PPV)){
			$this->PPV = $voBiomarkerOrganStudyData->PPV;
		}
		if(!empty($voBiomarkerOrganStudyData->NPV)){
			$this->NPV = $voBiomarkerOrganStudyData->NPV;
		}
		if(!empty($voBiomarkerOrganStudyData->Assay)){
			$this->Assay = $voBiomarkerOrganStudyData->Assay;
		}
		if(!empty($voBiomarkerOrganStudyData->Technology)){
			$this->Technology = $voBiomarkerOrganStudyData->Technology;
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