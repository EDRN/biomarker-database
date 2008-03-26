<?php
class StudyVars {
	const STU_OBJID = "objId";
	const STU_EDRNID = "EDRNID";
	const STU_FHCRC_ID = "FHCRC_ID";
	const STU_DMCC_ID = "DMCC_ID";
	const STU_TITLE = "Title";
	const STU_ABSTRACT = "Abstract";
	const STU_BIOMARKERPOPULATIONCHARACTERISTICS = "BiomarkerPopulationCharacteristics";
	const STU_BPCDESCRIPTION = "BPCDescription";
	const STU_DESIGN = "Design";
	const STU_DESIGNDESCRIPTION = "DesignDescription";
	const STU_BIOMARKERSTUDYTYPE = "BiomarkerStudyType";
	const STU_BIOMARKERS = "Biomarkers";
	const STU_BIOMARKERORGANS = "BiomarkerOrgans";
	const STU_BIOMARKERORGANDATAS = "BiomarkerOrganDatas";
	const STU_PUBLICATIONS = "Publications";
	const STU_RESOURCES = "Resources";
}

class objStudy {

	const _TYPE = "Study";
	private $XPress;
	public $BiomarkerPopulationCharacteristicsEnumValues = array("Case/Control","Longitudinal","Randomized");
	public $DesignEnumValues = array("Retrospective","Prospective Analysis","Cross Sectional");
	public $BiomarkerStudyTypeEnumValues = array("Registered","Unregistered");
	public $objId = '';
	public $EDRNID = '';
	public $FHCRC_ID = '';
	public $DMCC_ID = '';
	public $Title = '';
	public $Abstract = '';
	public $BiomarkerPopulationCharacteristics = '';
	public $BPCDescription = '';
	public $Design = '';
	public $DesignDescription = '';
	public $BiomarkerStudyType = '';
	public $Biomarkers = array();
	public $BiomarkerOrgans = array();
	public $BiomarkerOrganDatas = array();
	public $Publications = array();
	public $Resources = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Study<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		if ($objId == 0) { return false; /* must not be zero */ }
		$this->objId = $objId;
		$q = "SELECT * FROM `Study` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->EDRNID = $result['EDRNID'];
			$this->FHCRC_ID = $result['FHCRC_ID'];
			$this->DMCC_ID = $result['DMCC_ID'];
			$this->Title = $result['Title'];
			$this->Abstract = $result['Abstract'];
			$this->BiomarkerPopulationCharacteristics = $result['BiomarkerPopulationCharacteristics'];
			$this->BPCDescription = $result['BPCDescription'];
			$this->Design = $result['Design'];
			$this->DesignDescription = $result['DesignDescription'];
			$this->BiomarkerStudyType = $result['BiomarkerStudyType'];
		}
		if ($inflate){
			return $this->inflate($parentObjects);
		} else {
			return true;
		}
	}
	public function initializeByUniqueKey($key,$value,$inflate = true,$parentObjects = array()) {
		switch ($key) {
			case "Title":
				$this->Title = $value;
				$q = "SELECT * FROM `Study` WHERE `Title`=\"{$value}\" LIMIT 1";
				$r = $this->XPress->Database->safeQuery($q);
				if ($r->numRows() != 1){
					return false;
				} else {
					$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
					$this->objId = $result['objId'];
					$this->EDRNID = $result['EDRNID'];
					$this->FHCRC_ID = $result['FHCRC_ID'];
					$this->DMCC_ID = $result['DMCC_ID'];
					$this->Title = $result['Title'];
					$this->Abstract = $result['Abstract'];
					$this->BiomarkerPopulationCharacteristics = $result['BiomarkerPopulationCharacteristics'];
					$this->BPCDescription = $result['BPCDescription'];
					$this->Design = $result['Design'];
					$this->DesignDescription = $result['DesignDescription'];
					$this->BiomarkerStudyType = $result['BiomarkerStudyType'];
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
		$this->EDRNID = '';
		$this->FHCRC_ID = '';
		$this->DMCC_ID = '';
		$this->Title = '';
		$this->Abstract = '';
		$this->BiomarkerPopulationCharacteristics = '';
		$this->BPCDescription = '';
		$this->Design = '';
		$this->DesignDescription = '';
		$this->BiomarkerStudyType = '';
		$this->Biomarkers = array();
		$this->BiomarkerOrgans = array();
		$this->BiomarkerOrganDatas = array();
		$this->Publications = array();
		$this->Resources = array();
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getEDRNID() {
		 return $this->EDRNID;
	}
	public function getFHCRC_ID() {
		 return $this->FHCRC_ID;
	}
	public function getDMCC_ID() {
		 return $this->DMCC_ID;
	}
	public function getTitle() {
		 return $this->Title;
	}
	public function getAbstract() {
		 return $this->Abstract;
	}
	public function getBiomarkerPopulationCharacteristics() {
		 return $this->BiomarkerPopulationCharacteristics;
	}
	public function getBPCDescription() {
		 return $this->BPCDescription;
	}
	public function getDesign() {
		 return $this->Design;
	}
	public function getDesignDescription() {
		 return $this->DesignDescription;
	}
	public function getBiomarkerStudyType() {
		 return $this->BiomarkerStudyType;
	}
	public function getBiomarkers() {
		 return $this->Biomarkers;
	}
	public function getBiomarkerOrgans() {
		 return $this->BiomarkerOrgans;
	}
	public function getBiomarkerOrganDatas() {
		 return $this->BiomarkerOrganDatas;
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
	public function setEDRNID($value,$bSave = true) {
		$this->EDRNID = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setFHCRC_ID($value,$bSave = true) {
		$this->FHCRC_ID = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setDMCC_ID($value,$bSave = true) {
		$this->DMCC_ID = $value;
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
	public function setAbstract($value,$bSave = true) {
		$this->Abstract = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setBiomarkerPopulationCharacteristics($value,$bSave = true) {
		$this->BiomarkerPopulationCharacteristics = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setBPCDescription($value,$bSave = true) {
		$this->BPCDescription = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setDesign($value,$bSave = true) {
		$this->Design = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setDesignDescription($value,$bSave = true) {
		$this->DesignDescription = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setBiomarkerStudyType($value,$bSave = true) {
		$this->BiomarkerStudyType = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function create($Title){
		$this->Title = $Title;
		$this->save();
	}
	public function inflate($parentObjects = array()) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "Biomarkers":
		$q = "SELECT BiomarkerStudyDataID AS objId FROM xr_BiomarkerStudyData_Study WHERE StudyID = {$this->objId} AND StudyVar = \"Biomarkers\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerStudyData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Biomarkers[] = $obj;
			}
			$rcount++;
		}
		// Inflate "BiomarkerOrganDatas":
		$q = "SELECT BiomarkerOrganStudyDataID AS objId FROM xr_BiomarkerOrganStudyData_Study WHERE StudyID = {$this->objId} AND StudyVar = \"BiomarkerOrganDatas\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerOrganStudyData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->BiomarkerOrganDatas[] = $obj;
			}
			$rcount++;
		}
		// Inflate "BiomarkerOrgans":
		$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_Study_BiomarkerOrganData WHERE StudyID = {$this->objId} AND StudyVar = \"BiomarkerOrgans\" ";
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
		// Inflate "Publications":
		$q = "SELECT PublicationID AS objId FROM xr_Study_Publication WHERE StudyID = {$this->objId} AND StudyVar = \"Publications\" ";
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
		$q = "SELECT ResourceID AS objId FROM xr_Study_Resource WHERE StudyID = {$this->objId} AND StudyVar = \"Resources\" ";
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
			$q = "INSERT INTO `Study` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->EDRNID.'","'.$this->FHCRC_ID.'","'.$this->DMCC_ID.'","'.$this->Title.'","'.$this->Abstract.'","'.$this->BiomarkerPopulationCharacteristics.'","'.$this->BPCDescription.'","'.$this->Design.'","'.$this->DesignDescription.'","'.$this->BiomarkerStudyType.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Study`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Study` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`EDRNID`=\"$this->EDRNID\","; 
			$q .= "`FHCRC_ID`=\"$this->FHCRC_ID\","; 
			$q .= "`DMCC_ID`=\"$this->DMCC_ID\","; 
			$q .= "`Title`=\"$this->Title\","; 
			$q .= "`Abstract`=\"$this->Abstract\","; 
			$q .= "`BiomarkerPopulationCharacteristics`=\"$this->BiomarkerPopulationCharacteristics\","; 
			$q .= "`BPCDescription`=\"$this->BPCDescription\","; 
			$q .= "`Design`=\"$this->Design\","; 
			$q .= "`DesignDescription`=\"$this->DesignDescription\","; 
			$q .= "`BiomarkerStudyType`=\"$this->BiomarkerStudyType\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(StudyVars::STU_BIOMARKERS);
		$this->unlink(StudyVars::STU_BIOMARKERORGANS);
		$this->unlink(StudyVars::STU_BIOMARKERORGANDATAS);
		$this->unlink(StudyVars::STU_PUBLICATIONS);
		$this->unlink(StudyVars::STU_RESOURCES);
		//Delete this object's child objects
		foreach ($this->Biomarkers as $obj){
			$obj->delete();
		}
		foreach ($this->BiomarkerOrgans as $obj){
			$obj->delete();
		}
		foreach ($this->BiomarkerOrganDatas as $obj){
			$obj->delete();
		}

		//Delete object from the database
		$q = "DELETE FROM `Study` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Study";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarkers":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Study WHERE StudyID=$this->objId AND BiomarkerStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Study (StudyID,BiomarkerStudyDataID,StudyVar".(($remoteVar == '')? '' : ',BiomarkerStudyDataVar').") VALUES($this->objId,$remoteID,\"Biomarkers\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Study SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerStudyDataVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrganDatas":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Study WHERE StudyID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Study (StudyID,BiomarkerOrganStudyDataID,StudyVar".(($remoteVar == '')? '' : ',BiomarkerOrganStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrganDatas\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Study SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganStudyDataVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrgans":
				$q  = "SELECT COUNT(*) FROM xr_Study_BiomarkerOrganData WHERE StudyID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_BiomarkerOrganData (StudyID,BiomarkerOrganDataID,StudyVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrgans\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_BiomarkerOrganData SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_Study_Publication WHERE StudyID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_Publication (StudyID,PublicationID,StudyVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_Publication SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_Study_Resource WHERE StudyID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_Resource (StudyID,ResourceID,StudyVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_Resource SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
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
				$q = "DELETE FROM xr_BiomarkerStudyData_Study WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"Biomarkers\" ";
				break;
			case "BiomarkerOrganDatas":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Study WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"BiomarkerOrganDatas\" ";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM xr_Study_BiomarkerOrganData WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"BiomarkerOrgans\" ";
				break;
			case "Publications":
				$q = "DELETE FROM xr_Study_Publication WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PublicationID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"Publications\" ";
				break;
			case "Resources":
				$q = "DELETE FROM xr_Study_Resource WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND ResourceID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"Resources\" ";
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
				StudyXref::deleteByIDs($this->ID,"BiomarkerStudyData",$objectID,"Biomarkers");
				BiomarkerStudyData::Delete($objectID);
				break;
			case "BiomarkerOrgans":
				StudyXref::deleteByIDs($this->ID,"BiomarkerOrganData",$objectID,"BiomarkerOrgans");
				BiomarkerOrganData::Delete($objectID);
				break;
			case "BiomarkerOrganDatas":
				StudyXref::deleteByIDs($this->ID,"BiomarkerOrganStudyData",$objectID,"BiomarkerOrganDatas");
				BiomarkerOrganStudyData::Delete($objectID);
				break;
			case "Publications":
				StudyXref::deleteByIDs($this->ID,"Publication",$objectID,"Publications");
				break;
			case "Resources":
				StudyXref::deleteByIDs($this->ID,"Resource",$objectID,"Resources");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voStudy();
		$vo->objId = $this->objId;
		$vo->EDRNID = $this->EDRNID;
		$vo->FHCRC_ID = $this->FHCRC_ID;
		$vo->DMCC_ID = $this->DMCC_ID;
		$vo->Title = $this->Title;
		$vo->Abstract = $this->Abstract;
		$vo->BiomarkerPopulationCharacteristics = $this->BiomarkerPopulationCharacteristics;
		$vo->BPCDescription = $this->BPCDescription;
		$vo->Design = $this->Design;
		$vo->DesignDescription = $this->DesignDescription;
		$vo->BiomarkerStudyType = $this->BiomarkerStudyType;
		return $vo;
	}
	public function applyVO($voStudy) {
		if(!empty($voStudy->objId)){
			$this->objId = $voStudy->objId;
		}
		if(!empty($voStudy->EDRNID)){
			$this->EDRNID = $voStudy->EDRNID;
		}
		if(!empty($voStudy->FHCRC_ID)){
			$this->FHCRC_ID = $voStudy->FHCRC_ID;
		}
		if(!empty($voStudy->DMCC_ID)){
			$this->DMCC_ID = $voStudy->DMCC_ID;
		}
		if(!empty($voStudy->Title)){
			$this->Title = $voStudy->Title;
		}
		if(!empty($voStudy->Abstract)){
			$this->Abstract = $voStudy->Abstract;
		}
		if(!empty($voStudy->BiomarkerPopulationCharacteristics)){
			$this->BiomarkerPopulationCharacteristics = $voStudy->BiomarkerPopulationCharacteristics;
		}
		if(!empty($voStudy->BPCDescription)){
			$this->BPCDescription = $voStudy->BPCDescription;
		}
		if(!empty($voStudy->Design)){
			$this->Design = $voStudy->Design;
		}
		if(!empty($voStudy->DesignDescription)){
			$this->DesignDescription = $voStudy->DesignDescription;
		}
		if(!empty($voStudy->BiomarkerStudyType)){
			$this->BiomarkerStudyType = $voStudy->BiomarkerStudyType;
		}
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Study rdf:about=\"{$urlBase}/editors/showStudy.php?s={$this->ID}\">\r\n<{$namespace}:objId>$this->objId</{$namespace}:objId>\r\n<{$namespace}:EDRNID>$this->EDRNID</{$namespace}:EDRNID>\r\n<{$namespace}:FHCRC_ID>$this->FHCRC_ID</{$namespace}:FHCRC_ID>\r\n<{$namespace}:DMCC_ID>$this->DMCC_ID</{$namespace}:DMCC_ID>\r\n<{$namespace}:Title>$this->Title</{$namespace}:Title>\r\n<{$namespace}:Abstract>$this->Abstract</{$namespace}:Abstract>\r\n<{$namespace}:BiomarkerPopulationCharacteristics>$this->BiomarkerPopulationCharacteristics</{$namespace}:BiomarkerPopulationCharacteristics>\r\n<{$namespace}:BPCDescription>$this->BPCDescription</{$namespace}:BPCDescription>\r\n<{$namespace}:Design>$this->Design</{$namespace}:Design>\r\n<{$namespace}:DesignDescription>$this->DesignDescription</{$namespace}:DesignDescription>\r\n<{$namespace}:BiomarkerStudyType>$this->BiomarkerStudyType</{$namespace}:BiomarkerStudyType>\r\n";
		foreach ($this->Biomarkers as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->BiomarkerOrgans as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->BiomarkerOrganDatas as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->Publications as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->Resources as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:Study>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Study rdf:about=\"{$urlBase}/editors/showStudy.php?s={$this->ID}\"/>\r\n";
		return $rdf;
	}
}

?>