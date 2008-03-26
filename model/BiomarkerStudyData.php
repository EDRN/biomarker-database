<?php
class BiomarkerStudyDataVars {
	const BIO_OBJID = "objId";
	const BIO_SENSITIVITY = "Sensitivity";
	const BIO_SPECIFICITY = "Specificity";
	const BIO_PPV = "PPV";
	const BIO_NPV = "NPV";
	const BIO_ASSAY = "Assay";
	const BIO_TECHNOLOGY = "Technology";
	const BIO_STUDY = "Study";
	const BIO_BIOMARKER = "Biomarker";
	const BIO_PUBLICATIONS = "Publications";
	const BIO_RESOURCES = "Resources";
}

class objBiomarkerStudyData {

	const _TYPE = "BiomarkerStudyData";
	private $XPress;
	public $objId = '';
	public $Sensitivity = '';
	public $Specificity = '';
	public $PPV = '';
	public $NPV = '';
	public $Assay = '';
	public $Technology = '';
	public $Study = '';
	public $Biomarker = '';
	public $Publications = array();
	public $Resources = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type BiomarkerStudyData<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		if ($objId == 0) { return false; /* must not be zero */ }
		$this->objId = $objId;
		$q = "SELECT * FROM `BiomarkerStudyData` WHERE objId={$this->objId} LIMIT 1";
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
		$this->Biomarker = '';
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
	public function getBiomarker() {
		 return $this->Biomarker;
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
	public function create($StudyId,$BiomarkerId){
		$this->save();
		$this->link("Study",$StudyId,"Biomarkers");
		$this->link("Biomarker",$BiomarkerId,"Studies");
	}
	public function inflate($parentObjects = array()) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "Biomarker":
		$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Biomarker\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarker($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Biomarker = $obj;
			}
			$rcount++;
		}
		// Inflate "Study":
		$q = "SELECT StudyID AS objId FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Study\" ";
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
		$q = "SELECT PublicationID AS objId FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Publications\" ";
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
		$q = "SELECT ResourceID AS objId FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Resources\" ";
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
			$q = "INSERT INTO `BiomarkerStudyData` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->Sensitivity.'","'.$this->Specificity.'","'.$this->PPV.'","'.$this->NPV.'","'.$this->Assay.'","'.$this->Technology.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `BiomarkerStudyData`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `BiomarkerStudyData` SET ";
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
		$this->unlink(BiomarkerStudyDataVars::BIO_STUDY);
		$this->unlink(BiomarkerStudyDataVars::BIO_BIOMARKER);
		$this->unlink(BiomarkerStudyDataVars::BIO_PUBLICATIONS);
		$this->unlink(BiomarkerStudyDataVars::BIO_RESOURCES);
		//Delete this object's child objects

		//Delete object from the database
		$q = "DELETE FROM `BiomarkerStudyData` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "BiomarkerStudyData";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarker":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerStudyData (BiomarkerStudyDataID,BiomarkerID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarker\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerStudyData SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "Study":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Study (BiomarkerStudyDataID,StudyID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Study\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Study SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Publication (BiomarkerStudyDataID,PublicationID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Publication SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Resource (BiomarkerStudyDataID,ResourceID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Resource SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
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
			case "Biomarker":
				$q = "DELETE FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerStudyDataVar = \"Biomarker\" ";
				break;
			case "Study":
				$q = "DELETE FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND StudyID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerStudyDataVar = \"Study\" ";
				break;
			case "Publications":
				$q = "DELETE FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PublicationID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerStudyDataVar = \"Publications\" ";
				break;
			case "Resources":
				$q = "DELETE FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND ResourceID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerStudyDataVar = \"Resources\" ";
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
				BiomarkerStudyDataXref::deleteByIDs($this->ID,"Study",$objectID,"Study");
				break;
			case "Biomarker":
				BiomarkerStudyDataXref::deleteByIDs($this->ID,"Biomarker",$objectID,"Biomarker");
				break;
			case "Publications":
				BiomarkerStudyDataXref::deleteByIDs($this->ID,"Publication",$objectID,"Publications");
				break;
			case "Resources":
				BiomarkerStudyDataXref::deleteByIDs($this->ID,"Resource",$objectID,"Resources");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voBiomarkerStudyData();
		$vo->objId = $this->objId;
		$vo->Sensitivity = $this->Sensitivity;
		$vo->Specificity = $this->Specificity;
		$vo->PPV = $this->PPV;
		$vo->NPV = $this->NPV;
		$vo->Assay = $this->Assay;
		$vo->Technology = $this->Technology;
		return $vo;
	}
	public function applyVO($voBiomarkerStudyData) {
		if(!empty($voBiomarkerStudyData->objId)){
			$this->objId = $voBiomarkerStudyData->objId;
		}
		if(!empty($voBiomarkerStudyData->Sensitivity)){
			$this->Sensitivity = $voBiomarkerStudyData->Sensitivity;
		}
		if(!empty($voBiomarkerStudyData->Specificity)){
			$this->Specificity = $voBiomarkerStudyData->Specificity;
		}
		if(!empty($voBiomarkerStudyData->PPV)){
			$this->PPV = $voBiomarkerStudyData->PPV;
		}
		if(!empty($voBiomarkerStudyData->NPV)){
			$this->NPV = $voBiomarkerStudyData->NPV;
		}
		if(!empty($voBiomarkerStudyData->Assay)){
			$this->Assay = $voBiomarkerStudyData->Assay;
		}
		if(!empty($voBiomarkerStudyData->Technology)){
			$this->Technology = $voBiomarkerStudyData->Technology;
		}
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		if ($this->Study != ''){$rdf .= $this->Study->toRDFStub($namespace,$urlBase);}
		if ($this->Biomarker != ''){$rdf .= $this->Biomarker->toRDFStub($namespace,$urlBase);}
		foreach ($this->Publications as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->Resources as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		if($this->Study != ''){$rdf .= $this->Study->toRDFStub($namespace,$urlBase);}
		if($this->Biomarker != ''){$rdf .= $this->Biomarker->toRDFStub($namespace,$urlBase);}
		foreach ($this->Publications as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->Resources as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		return $rdf;
	}
}

?>