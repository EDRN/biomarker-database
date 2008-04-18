<?php


class BiomarkerStudyDataVars {
	const OBJID = "objId";
	const SENSITIVITY = "Sensitivity";
	const SPECIFICITY = "Specificity";
	const PPV = "PPV";
	const NPV = "NPV";
	const ASSAY = "Assay";
	const TECHNOLOGY = "Technology";
	const STUDY = "Study";
	const BIOMARKER = "Biomarker";
	const PUBLICATIONS = "Publications";
	const RESOURCES = "Resources";
}

class BiomarkerStudyDataFactory {
	public static function Create($StudyId,$BiomarkerId){
		$o = new BiomarkerStudyData();
		$o->save();
		$o->link(BiomarkerStudyDataVars::STUDY,$StudyId,StudyVars::BIOMARKERS);
		$o->link(BiomarkerStudyDataVars::BIOMARKER,$BiomarkerId,BiomarkerVars::STUDIES);
		return $o;
	}
	public static function Retrieve($value,$key = BiomarkerStudyDataVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new BiomarkerStudyData();
		switch ($key) {
			case BiomarkerStudyDataVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `BiomarkerStudyData` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setSensitivity($data['Sensitivity'],false);
				$o->setSpecificity($data['Specificity'],false);
				$o->setPPV($data['PPV'],false);
				$o->setNPV($data['NPV'],false);
				$o->setAssay($data['Assay'],false);
				$o->setTechnology($data['Technology'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class BiomarkerStudyData extends XPressObject {

	const _TYPE = "BiomarkerStudyData";
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


	public function __construct($objId = 0) {
		//echo "creating object of type BiomarkerStudyData<br/>";
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
			$this->inflate(BiomarkerStudyDataVars::STUDY);
			return $this->Study;
		}
	}
	public function getBiomarker() {
		if ($this->Biomarker != "") {
			return $this->Biomarker;
		} else {
			$this->inflate(BiomarkerStudyDataVars::BIOMARKER);
			return $this->Biomarker;
		}
	}
	public function getPublications() {
		if ($this->Publications != array()) {
			return $this->Publications;
		} else {
			$this->inflate(BiomarkerStudyDataVars::PUBLICATIONS);
			return $this->Publications;
		}
	}
	public function getResources() {
		if ($this->Resources != array()) {
			return $this->Resources;
		} else {
			$this->inflate(BiomarkerStudyDataVars::RESOURCES);
			return $this->Resources;
		}
	}

	// Mutator Functions 
	public function setSensitivity($value,$bSave = true) {
		$this->Sensitivity = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::SENSITIVITY);
		}
	}
	public function setSpecificity($value,$bSave = true) {
		$this->Specificity = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::SPECIFICITY);
		}
	}
	public function setPPV($value,$bSave = true) {
		$this->PPV = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::PPV);
		}
	}
	public function setNPV($value,$bSave = true) {
		$this->NPV = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::NPV);
		}
	}
	public function setAssay($value,$bSave = true) {
		$this->Assay = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::ASSAY);
		}
	}
	public function setTechnology($value,$bSave = true) {
		$this->Technology = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::TECHNOLOGY);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Biomarker":
				// Inflate "Biomarker":
				$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Biomarker\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->Biomarker = BiomarkerFactory::retrieve($id[objId]);
				}
				break;
			case "Study":
				// Inflate "Study":
				$q = "SELECT StudyID AS objId FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Study\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->Study = StudyFactory::retrieve($id[objId]);
				}
				break;
			case "Publications":
				// Inflate "Publications":
				$q = "SELECT PublicationID AS objId FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Publications\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Publications = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Publications[] = PublicationFactory::retrieve($id[objId]);
				}
				break;
			case "Resources":
				// Inflate "Resources":
				$q = "SELECT ResourceID AS objId FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Resources\" ";
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
		$this->Biomarker = '';
		$this->Publications = array();
		$this->Resources = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `BiomarkerStudyData` ";
			$q .= 'VALUES("","'.$this->Sensitivity.'","'.$this->Specificity.'","'.$this->PPV.'","'.$this->NPV.'","'.$this->Assay.'","'.$this->Technology.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `BiomarkerStudyData`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `BiomarkerStudyData` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `BiomarkerStudyData` SET ";
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
		$this->unlink(BiomarkerStudyDataVars::STUDY);
		$this->unlink(BiomarkerStudyDataVars::BIOMARKER);
		$this->unlink(BiomarkerStudyDataVars::PUBLICATIONS);
		$this->unlink(BiomarkerStudyDataVars::RESOURCES);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerStudyData WHERE `BiomarkerStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerStudyData_Study WHERE `BiomarkerStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerStudyData_Publication WHERE `BiomarkerStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerStudyData_Resource WHERE `BiomarkerStudyDataID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM BiomarkerStudyData WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return BiomarkerStudyData::_TYPE; //BiomarkerStudyData
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarker":
				$test = "SELECT COUNT(*) FROM Biomarker WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerStudyData (BiomarkerStudyDataID,BiomarkerID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarker\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerStudyData SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "Study":
				$test = "SELECT COUNT(*) FROM Study WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Study (BiomarkerStudyDataID,StudyID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Study\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Study SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$test = "SELECT COUNT(*) FROM Publication WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Publication (BiomarkerStudyDataID,PublicationID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Publication SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$test = "SELECT COUNT(*) FROM Resource WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Resource (BiomarkerStudyDataID,ResourceID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Resource SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
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