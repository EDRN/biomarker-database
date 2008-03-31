<?php
// Generated by CWSP XPress Modeling Utility
//	on 2008-03-31 09:15:25


class BiomarkerOrganStudyDataVars {
	const OBJID = "objId";
	const SENSITIVITY = "Sensitivity";
	const SPECIFICITY = "Specificity";
	const PPV = "PPV";
	const NPV = "NPV";
	const ASSAY = "Assay";
	const TECHNOLOGY = "Technology";
	const STUDY = "Study";
	const BIOMARKERORGANDATA = "BiomarkerOrganData";
	const PUBLICATIONS = "Publications";
	const RESOURCES = "Resources";
	const STUDY = "Study";
	const BIOMARKERORGANDATA = "BiomarkerOrganData";
	const PUBLICATIONS = "Publications";
	const RESOURCES = "Resources";
}

class BiomarkerOrganStudyDataFactory {
	public static function Create($StudyId,$BiomarkerOrganDataId,$StudyId,$BiomarkerOrganDataId){
		$o = new BiomarkerOrganStudyData();
		$o->save();
		$o->link(BiomarkerOrganStudyDataVars::STUDY,$StudyId,StudyVars::BIOMARKERORGANDATAS);
		$o->link(BiomarkerOrganStudyDataVars::BIOMARKERORGANDATA,$BiomarkerOrganDataId,BiomarkerOrganDataVars::STUDYDATAS);
		$o->link(BiomarkerOrganStudyDataVars::STUDY,$StudyId,StudyVars::BIOMARKERORGANDATAS);
		$o->link(BiomarkerOrganStudyDataVars::BIOMARKERORGANDATA,$BiomarkerOrganDataId,BiomarkerOrganDataVars::STUDYDATAS);
		return $o;
	}
	public static function Retrieve($value,$key = BiomarkerOrganStudyDataVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new BiomarkerOrganStudyData();
		switch ($key) {
			case BiomarkerOrganStudyDataVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `BiomarkerOrganStudyData` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setSensitivity($data['Sensitivity']);
				$o->setSpecificity($data['Specificity']);
				$o->setPPV($data['PPV']);
				$o->setNPV($data['NPV']);
				$o->setAssay($data['Assay']);
				$o->setTechnology($data['Technology']);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class BiomarkerOrganStudyData extends XPressObject {

	const _TYPE = "BiomarkerOrganStudyData";
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
	public $Study = '';
	public $BiomarkerOrganData = '';
	public $Publications = array();
	public $Resources = array();


	public function __construct($objId = 0) {
		//echo "creating object of type BiomarkerOrganStudyData<br/>";
		$this->objId = $objId;
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
		if ($this->Study != "") {
			return $this->Study;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::STUDY);
			return $this->Study;
		}
	}
	public function getBiomarkerOrganData() {
		if ($this->BiomarkerOrganData != "") {
			return $this->BiomarkerOrganData;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::BIOMARKERORGANDATA);
			return $this->BiomarkerOrganData;
		}
	}
	public function getPublications() {
		if ($this->Publications != array()) {
			return $this->Publications;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::PUBLICATIONS);
			return $this->Publications;
		}
	}
	public function getResources() {
		if ($this->Resources != array()) {
			return $this->Resources;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::RESOURCES);
			return $this->Resources;
		}
	}
	public function getStudy() {
		if ($this->Study != "") {
			return $this->Study;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::STUDY);
			return $this->Study;
		}
	}
	public function getBiomarkerOrganData() {
		if ($this->BiomarkerOrganData != "") {
			return $this->BiomarkerOrganData;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::BIOMARKERORGANDATA);
			return $this->BiomarkerOrganData;
		}
	}
	public function getPublications() {
		if ($this->Publications != array()) {
			return $this->Publications;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::PUBLICATIONS);
			return $this->Publications;
		}
	}
	public function getResources() {
		if ($this->Resources != array()) {
			return $this->Resources;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::RESOURCES);
			return $this->Resources;
		}
	}

	// Mutator Functions 
	public function setSensitivity($value,$bSave = true) {
		$this->Sensitivity = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::SENSITIVITY);
		}
	}
	public function setSpecificity($value,$bSave = true) {
		$this->Specificity = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::SPECIFICITY);
		}
	}
	public function setPPV($value,$bSave = true) {
		$this->PPV = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::PPV);
		}
	}
	public function setNPV($value,$bSave = true) {
		$this->NPV = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::NPV);
		}
	}
	public function setAssay($value,$bSave = true) {
		$this->Assay = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::ASSAY);
		}
	}
	public function setTechnology($value,$bSave = true) {
		$this->Technology = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::TECHNOLOGY);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "BiomarkerOrganData":
				// Inflate "BiomarkerOrganData":
				$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"BiomarkerOrganData\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->BiomarkerOrganData = BiomarkerOrganDataFactory::retrieve($id[objId]);
				}
				break;
			case "Study":
				// Inflate "Study":
				$q = "SELECT StudyID AS objId FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"Study\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->Study = StudyFactory::retrieve($id[objId]);
				}
				break;
			case "Publications":
				// Inflate "Publications":
				$q = "SELECT PublicationID AS objId FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"Publications\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Publications = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Publications[] = PublicationFactory::retrieve($id[objId]);
				}
				break;
			case "Resources":
				// Inflate "Resources":
				$q = "SELECT ResourceID AS objId FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"Resources\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Resources = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Resources[] = ResourceFactory::retrieve($id[objId]);
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
		$this->Study = '';
		$this->BiomarkerOrganData = '';
		$this->Publications = array();
		$this->Resources = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `BiomarkerOrganStudyData` ";
			$q .= 'VALUES("","'.$this->Sensitivity.'","'.$this->Specificity.'","'.$this->PPV.'","'.$this->NPV.'","'.$this->Assay.'","'.$this->Technology.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `BiomarkerOrganStudyData`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `BiomarkerOrganStudyData` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `BiomarkerOrganStudyData` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`Sensitivity`=\"{$this->Sensitivity}\","; 
				$q .= "`Specificity`=\"{$this->Specificity}\","; 
				$q .= "`PPV`=\"{$this->PPV}\","; 
				$q .= "`NPV`=\"{$this->NPV}\","; 
				$q .= "`Assay`=\"{$this->Assay}\","; 
				$q .= "`Technology`=\"{$this->Technology}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerOrganStudyDataVars::STUDY);
		$this->unlink(BiomarkerOrganStudyDataVars::BIOMARKERORGANDATA);
		$this->unlink(BiomarkerOrganStudyDataVars::PUBLICATIONS);
		$this->unlink(BiomarkerOrganStudyDataVars::RESOURCES);
		$this->unlink(BiomarkerOrganStudyDataVars::STUDY);
		$this->unlink(BiomarkerOrganStudyDataVars::BIOMARKERORGANDATA);
		$this->unlink(BiomarkerOrganStudyDataVars::PUBLICATIONS);
		$this->unlink(BiomarkerOrganStudyDataVars::RESOURCES);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE `BiomarkerOrganStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganStudyData_Study WHERE `BiomarkerOrganStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganStudyData_Publication WHERE `BiomarkerOrganStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganStudyData_Resource WHERE `BiomarkerOrganStudyDataID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM BiomarkerOrganStudyData WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return objBiomarkerOrganStudyData::_TYPE; //BiomarkerOrganStudyData
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "BiomarkerOrganData":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_BiomarkerOrganStudyData (BiomarkerOrganStudyDataID,BiomarkerOrganDataID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrganData\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_BiomarkerOrganStudyData SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Study":
				$test = "SELECT COUNT(*) FROM Study WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Study (BiomarkerOrganStudyDataID,StudyID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Study\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Study SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$test = "SELECT COUNT(*) FROM Publication WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Publication (BiomarkerOrganStudyDataID,PublicationID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Publication SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$test = "SELECT COUNT(*) FROM Resource WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Resource (BiomarkerOrganStudyDataID,ResourceID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Resource SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
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