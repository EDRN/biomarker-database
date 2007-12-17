<?php
class Study {

	public static function Create($Title) {
		$vo = new voStudy();
		$vo->Title = $Title;
		$dao = new daoStudy();
		$dao->insert(&$vo);
		$obj = new objStudy();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoStudy();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchStudyException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objStudy();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->Biomarkers = array();
						$o->BiomarkerOrgans = array();
						$o->BiomarkerOrganDatas = array();
						$o->Publications = array();
						$o->Resources = array();
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->Biomarkers = StudyXref::retrieve($o,"BiomarkerStudyData",$po,$lazyFetch,$limit,"Biomarkers");
						if ($o->Biomarkers == null){$o->Biomarkers = array();}
						$o->BiomarkerOrgans = StudyXref::retrieve($o,"BiomarkerOrganData",$po,$lazyFetch,$limit,"BiomarkerOrgans");
						if ($o->BiomarkerOrgans == null){$o->BiomarkerOrgans = array();}
						$o->BiomarkerOrganDatas = StudyXref::retrieve($o,"BiomarkerOrganStudyData",$po,$lazyFetch,$limit,"BiomarkerOrganDatas");
						if ($o->BiomarkerOrganDatas == null){$o->BiomarkerOrganDatas = array();}
						$o->Publications = StudyXref::retrieve($o,"Publication",$po,$lazyFetch,$limit,"Publications");
						if ($o->Publications == null){$o->Publications = array();}
						$o->Resources = StudyXref::retrieve($o,"Resource",$po,$lazyFetch,$limit,"Resources");
						if ($o->Resources == null){$o->Resources = array();}
					}
				}
				if ($limit == 1) { return $o; /* Return a single built object */}
				else { if ($o != null){$objs[] = $o;}  /* Add the built object to the array */}
				if (sizeof($objs) == $limit) { return $objs; /* Force return after $limit results */ }
			}
			return $objs;  // Return all matched objects
		} else {
			 // no results matched the query! (Use $limit to determine what to return)
			 if ($limit == 1){return '';}
			 else {return array();}
		}
	}
	public static function RetrieveByID($ID,$lazyFetch=false) {
		$dao = new daoStudy();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchStudyException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objStudy;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Biomarkers = array();
			$obj->BiomarkerOrgans = array();
			$obj->BiomarkerOrganDatas = array();
			$obj->Publications = array();
			$obj->Resources = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Biomarkers = StudyXref::retrieve($obj,"BiomarkerStudyData",array($obj),$lazyFetch,$limit,"Biomarkers");
			if ($obj->Biomarkers == null){$obj->Biomarkers = array();}
			$obj->BiomarkerOrgans = StudyXref::retrieve($obj,"BiomarkerOrganData",array($obj),$lazyFetch,$limit,"BiomarkerOrgans");
			if ($obj->BiomarkerOrgans == null){$obj->BiomarkerOrgans = array();}
			$obj->BiomarkerOrganDatas = StudyXref::retrieve($obj,"BiomarkerOrganStudyData",array($obj),$lazyFetch,$limit,"BiomarkerOrganDatas");
			if ($obj->BiomarkerOrganDatas == null){$obj->BiomarkerOrganDatas = array();}
			$obj->Publications = StudyXref::retrieve($obj,"Publication",array($obj),$lazyFetch,$limit,"Publications");
			if ($obj->Publications == null){$obj->Publications = array();}
			$obj->Resources = StudyXref::retrieve($obj,"Resource",array($obj),$lazyFetch,$limit,"Resources");
			if ($obj->Resources == null){$obj->Resources = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		$rows = $db->safeGetAll("SELECT BiomarkerStudyDataID FROM `xr_BiomarkerStudyData_Study` WHERE StudyID IN (".implode(",",$objectIDs).")");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerStudyDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerStudyData::MultiDelete($goners,$db);}
		$rows = $db->safeGetAll("SELECT BiomarkerOrganDataID FROM `xr_Study_BiomarkerOrganData` WHERE StudyID IN (".implode(",",$objectIDs).")");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerOrganDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerOrganData::MultiDelete($goners,$db);}
		$rows = $db->safeGetAll("SELECT BiomarkerOrganStudyDataID FROM `xr_BiomarkerOrganStudyData_Study` WHERE StudyID IN (".implode(",",$objectIDs).")");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerOrganStudyDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerOrganStudyData::MultiDelete($goners,$db);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Study` WHERE StudyID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Study` WHERE StudyID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_Study_BiomarkerOrganData` WHERE StudyID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_Study_Publication` WHERE StudyID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_Study_Resource` WHERE StudyID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `Study` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		$rows = $db->safeGetAll("SELECT BiomarkerStudyDataID FROM `xr_BiomarkerStudyData_Study` WHERE StudyID = $objID ");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerStudyDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerStudyData::MultiDelete($goners,$db);}
		$rows = $db->safeGetAll("SELECT BiomarkerOrganDataID FROM `xr_Study_BiomarkerOrganData` WHERE StudyID = $objID ");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerOrganDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerOrganData::MultiDelete($goners,$db);}
		$rows = $db->safeGetAll("SELECT BiomarkerOrganStudyDataID FROM `xr_BiomarkerOrganStudyData_Study` WHERE StudyID = $objID ");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerOrganStudyDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerOrganStudyData::MultiDelete($goners,$db);}
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Study` WHERE StudyID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Study` WHERE StudyID = $objID");
		$db->safeQuery("DELETE FROM `xr_Study_BiomarkerOrganData` WHERE StudyID = $objID");
		$db->safeQuery("DELETE FROM `xr_Study_Publication` WHERE StudyID = $objID");
		$db->safeQuery("DELETE FROM `xr_Study_Resource` WHERE StudyID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `Study` WHERE ID = $objID");
	}
	public static function Exists($Title) {
		$dao = new daoStudy();
		try {
			$dao->getBy(array("Title"),array($Title));
		} catch (NoSuchStudyException $e){
			return false;
		}
		return true;
	}
	public static function RetrieveUnique( $Title,$lazyFetch=false) {
		$dao = new daoStudy();
		try {
			$results = $dao->getBy(array("Title"),array($Title));
			return Study::RetrieveByID($results[0]->ID,$lazyFetch);
		} catch (NoSuchStudyException $e){
			return false;
		}
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoStudy;
		$dao->save(&$vo);
	}
	public static function attach_Biomarker($object,$BiomarkerStudyData){
		$object->Biomarkers[] = $BiomarkerStudyData;
	}
	public static function attach_BiomarkerOrgan($object,$BiomarkerOrganData){
		$object->BiomarkerOrgans[] = $BiomarkerOrganData;
	}
	public static function attach_BiomarkerOrganData($object,$BiomarkerOrganStudyData){
		$object->BiomarkerOrganDatas[] = $BiomarkerOrganStudyData;
	}
	public static function attach_Publication($object,$Publication){
		$object->Publications[] = $Publication;
	}
	public static function attach_Resource($object,$Resource){
		$object->Resources[] = $Resource;
	}
}

class StudyVars {
	const STU_OBJID = "objId";
	const STU_EDRNID = "EDRNID";
	const STU_FHCRC_ID = "FHCRC_ID";
	const STU_DMCC_ID = "DMCC_ID";
	const STU_TITLE = "Title";
	const STU_ABSTRACT = "Abstract";
	const STU_BIOMARKERPOPULATIONCHARACTERISTICS = "BiomarkerPopulationCharacteristics";
	const STU_DESIGN = "Design";
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
	public $objId = '';
	public $EDRNID = '';
	public $FHCRC_ID = '';
	public $DMCC_ID = '';
	public $Title = '';
	public $Abstract = '';
	public $BiomarkerPopulationCharacteristics = '';
	public $Design = '';
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
			$this->Design = $result['Design'];
			$this->BiomarkerStudyType = $result['BiomarkerStudyType'];
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
		$this->EDRNID = '';
		$this->FHCRC_ID = '';
		$this->DMCC_ID = '';
		$this->Title = '';
		$this->Abstract = '';
		$this->BiomarkerPopulationCharacteristics = '';
		$this->Design = '';
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
	public function getDesign() {
		 return $this->Design;
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
	public function setDesign($value,$bSave = true) {
		$this->Design = $value;
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
			$q .= 'VALUES("'.$this->objId.'","'.$this->EDRNID.'","'.$this->FHCRC_ID.'","'.$this->DMCC_ID.'","'.$this->Title.'","'.$this->Abstract.'","'.$this->BiomarkerPopulationCharacteristics.'","'.$this->Design.'","'.$this->BiomarkerStudyType.'") ';
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
			$q .= "`Design`=\"$this->Design\","; 
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
		$json = '{';
		$json .= "\"objId\": \"{$this->objId}\", ";
		$json .= "\"EDRNID\": \"{$this->EDRNID}\", ";
		$json .= "\"FHCRC_ID\": \"{$this->FHCRC_ID}\", ";
		$json .= "\"DMCC_ID\": \"{$this->DMCC_ID}\", ";
		$json .= "\"Title\": \"{$this->Title}\", ";
		$json .= "\"Abstract\": \"{$this->Abstract}\", ";
		$json .= "\"BiomarkerPopulationCharacteristics\": \"{$this->BiomarkerPopulationCharacteristics}\", ";
		$json .= "\"Design\": \"{$this->Design}\", ";
		$json .= "\"BiomarkerStudyType\": \"{$this->BiomarkerStudyType}\", ";
		$json .= "\"Biomarkers\": [";
		$jsonSnippets = array();
		foreach ($this->Biomarkers as $var){
			$jsonSnippets[] = $var->toJSON();
		}
		$json .= implode(",",$jsonSnippets);
		$json .= "], ";
		$json .= "\"BiomarkerOrgans\": [";
		$jsonSnippets = array();
		foreach ($this->BiomarkerOrgans as $var){
			$jsonSnippets[] = $var->toJSON();
		}
		$json .= implode(",",$jsonSnippets);
		$json .= "], ";
		$json .= "\"BiomarkerOrganDatas\": [";
		$jsonSnippets = array();
		foreach ($this->BiomarkerOrganDatas as $var){
			$jsonSnippets[] = $var->toJSON();
		}
		$json .= implode(",",$jsonSnippets);
		$json .= "], ";
		$json .= "\"Publications\": [";
		$jsonSnippets = array();
		foreach ($this->Publications as $var){
			$jsonSnippets[] = $var->toJSON();
		}
		$json .= implode(",",$jsonSnippets);
		$json .= "], ";
		$json .= "\"Resources\": [";
		$jsonSnippets = array();
		foreach ($this->Resources as $var){
			$jsonSnippets[] = $var->toJSON();
		}
		$json .= implode(",",$jsonSnippets);
		$json .= "], ";
		$json .= "\"_objectType\": \"Study\"}";
		return ($json);
	}
	public function associate($objectID,$variableName) {
		switch ($variableName) {
			case "Biomarkers":
				StudyXref::createByIDs($this->ID,"BiomarkerStudyData",$objectID,"Biomarkers");
				break;
			case "BiomarkerOrgans":
				StudyXref::createByIDs($this->ID,"BiomarkerOrganData",$objectID,"BiomarkerOrgans");
				break;
			case "BiomarkerOrganDatas":
				StudyXref::createByIDs($this->ID,"BiomarkerOrganStudyData",$objectID,"BiomarkerOrganDatas");
				break;
			case "Publications":
				StudyXref::createByIDs($this->ID,"Publication",$objectID,"Publications");
				break;
			case "Resources":
				StudyXref::createByIDs($this->ID,"Resource",$objectID,"Resources");
				break;
			default: 
				return false;
		}
		return true;
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
		$vo->Design = $this->Design;
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
		if(!empty($voStudy->Design)){
			$this->Design = $voStudy->Design;
		}
		if(!empty($voStudy->BiomarkerStudyType)){
			$this->BiomarkerStudyType = $voStudy->BiomarkerStudyType;
		}
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Study rdf:about=\"{$urlBase}/editors/showStudy.php?s={$this->ID}\">\r\n<{$namespace}:objId>$this->objId</{$namespace}:objId>\r\n<{$namespace}:EDRNID>$this->EDRNID</{$namespace}:EDRNID>\r\n<{$namespace}:FHCRC_ID>$this->FHCRC_ID</{$namespace}:FHCRC_ID>\r\n<{$namespace}:DMCC_ID>$this->DMCC_ID</{$namespace}:DMCC_ID>\r\n<{$namespace}:Title>$this->Title</{$namespace}:Title>\r\n<{$namespace}:Abstract>$this->Abstract</{$namespace}:Abstract>\r\n<{$namespace}:BiomarkerPopulationCharacteristics>$this->BiomarkerPopulationCharacteristics</{$namespace}:BiomarkerPopulationCharacteristics>\r\n<{$namespace}:Design>$this->Design</{$namespace}:Design>\r\n<{$namespace}:BiomarkerStudyType>$this->BiomarkerStudyType</{$namespace}:BiomarkerStudyType>\r\n";
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

class voStudy {
	public $objId;
	public $EDRNID;
	public $FHCRC_ID;
	public $DMCC_ID;
	public $Title;
	public $Abstract;
	public $BiomarkerPopulationCharacteristics;
	public $Design;
	public $BiomarkerStudyType;

	public function toAssocArray(){
		return array(
			"objId" => $this->objId,
			"EDRNID" => $this->EDRNID,
			"FHCRC_ID" => $this->FHCRC_ID,
			"DMCC_ID" => $this->DMCC_ID,
			"Title" => $this->Title,
			"Abstract" => $this->Abstract,
			"BiomarkerPopulationCharacteristics" => $this->BiomarkerPopulationCharacteristics,
			"Design" => $this->Design,
			"BiomarkerStudyType" => $this->BiomarkerStudyType,
		);
	}
	public function applyAssocArray($arr) {
		if(!empty($arr['objId'])){
			$this->objId = $arr['objId'];
		}
		if(!empty($arr['EDRNID'])){
			$this->EDRNID = $arr['EDRNID'];
		}
		if(!empty($arr['FHCRC_ID'])){
			$this->FHCRC_ID = $arr['FHCRC_ID'];
		}
		if(!empty($arr['DMCC_ID'])){
			$this->DMCC_ID = $arr['DMCC_ID'];
		}
		if(!empty($arr['Title'])){
			$this->Title = $arr['Title'];
		}
		if(!empty($arr['Abstract'])){
			$this->Abstract = $arr['Abstract'];
		}
		if(!empty($arr['BiomarkerPopulationCharacteristics'])){
			$this->BiomarkerPopulationCharacteristics = $arr['BiomarkerPopulationCharacteristics'];
		}
		if(!empty($arr['Design'])){
			$this->Design = $arr['Design'];
		}
		if(!empty($arr['BiomarkerStudyType'])){
			$this->BiomarkerStudyType = $arr['BiomarkerStudyType'];
		}
	}
}

class daoStudy {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `Study` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchStudyException("No Study found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voStudy();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Study` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voStudy();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `Study` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voStudy();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Study`"; 
		$r = $this->conn->safeGetOne($q); 
		return($r);
	}

	public function save(&$vo){
		if ($vo->ID == 0) {
			echo "inserting new obj ".print_r($vo->toAssocArray(),true);
			$this->insert($vo);
		} else {
			echo "updating existing obj ".print_r($vo->toAssocArray(),true);
			$this->update($vo);
		}
	}

	public function delete(&$vo) {
		$q = "DELETE FROM `Study` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `Study` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "EDRNID=\"$vo->EDRNID\"" . ", ";
		$q .= "FHCRC_ID=\"$vo->FHCRC_ID\"" . ", ";
		$q .= "DMCC_ID=\"$vo->DMCC_ID\"" . ", ";
		$q .= "Title=\"$vo->Title\"" . ", ";
		$q .= "Abstract=\"$vo->Abstract\"" . ", ";
		$q .= "BiomarkerPopulationCharacteristics=\"$vo->BiomarkerPopulationCharacteristics\"" . ", ";
		$q .= "Design=\"$vo->Design\"" . ", ";
		$q .= "BiomarkerStudyType=\"$vo->BiomarkerStudyType\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Study` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->EDRNID.'","'.$vo->FHCRC_ID.'","'.$vo->DMCC_ID.'","'.$vo->Title.'","'.$vo->Abstract.'","'.$vo->BiomarkerPopulationCharacteristics.'","'.$vo->Design.'","'.$vo->BiomarkerStudyType.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Study`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->EDRNID = $result['EDRNID'];
		$vo->FHCRC_ID = $result['FHCRC_ID'];
		$vo->DMCC_ID = $result['DMCC_ID'];
		$vo->Title = $result['Title'];
		$vo->Abstract = $result['Abstract'];
		$vo->BiomarkerPopulationCharacteristics = $result['BiomarkerPopulationCharacteristics'];
		$vo->Design = $result['Design'];
		$vo->BiomarkerStudyType = $result['BiomarkerStudyType'];
	}

}

class NoSuchStudyException extends Exception {
	public function __construct($message,$code = 0){
		parent::__construct($message,$code);
	}

	public function __toString() {
		$str = "<strong>".__CLASS__." Occurred: </strong>";
		$str .= "(Code: {$this->code}) ";
		$str .= "[Message: {$this->message}]\n<br/>";
		if (DEBUGGING){
			$str .= "&nbsp;&nbsp; at " . self::getFile() . ":" . self::getLine();
			$str .= "<br/><br/>\n";
			$str .= self::getFormattedStackTrace();
		}
		return $str;
	}

	public function getFormattedStackTrace(){
		$trace = "<strong>Stack Trace:</strong><br/>";
		foreach (self::getTrace() as $file){
			$trace .= "At line $file[line] of $file[file] ";
			$trace .= "[";
			if (isset($file['class'])){
				$trace .= "$file[class]";
			}
			if (isset($file['function'])){
				$trace .= "::$file[function]";
			}
			$trace .= "]<br/>";
		}
		return $trace;
	}

}

class StudyXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "Biomarkers":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Study WHERE StudyID=$localID AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Study (StudyID,BiomarkerStudyDataID,StudyVar) VALUES($localID,$remoteID,\"Biomarkers\");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Study SET StudyVar=\"{$variableName}\" WHERE StudyID=$localID AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrganDatas":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Study WHERE StudyID=$localID AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Study (StudyID,BiomarkerOrganStudyDataID,StudyVar) VALUES($localID,$remoteID,\"BiomarkerOrganDatas\");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Study SET StudyVar=\"{$variableName}\" WHERE StudyID=$localID AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrgans":
				$q  = "SELECT COUNT(*) FROM xr_Study_BiomarkerOrganData WHERE StudyID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Study_BiomarkerOrganData (StudyID,BiomarkerOrganDataID,StudyVar) VALUES($localID,$remoteID,\"BiomarkerOrgans\");";
				$q1 = "UPDATE xr_Study_BiomarkerOrganData SET StudyVar=\"{$variableName}\" WHERE StudyID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_Study_Publication WHERE StudyID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Study_Publication (StudyID,PublicationID,StudyVar) VALUES($localID,$remoteID,\"Publications\");";
				$q1 = "UPDATE xr_Study_Publication SET StudyVar=\"{$variableName}\" WHERE StudyID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_Study_Resource WHERE StudyID=$localID AND ResourceID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Study_Resource (StudyID,ResourceID,StudyVar) VALUES($localID,$remoteID,\"Resources\");";
				$q1 = "UPDATE xr_Study_Resource SET StudyVar=\"{$variableName}\" WHERE StudyID=$localID AND ResourceID=$remoteID LIMIT 1 ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$count  = $db->safeGetOne($q);
		if ($count == 0){
			$db->safeQuery($q0);
		} else {
			$db->safeQuery($q1);
		}
		return true;
	}
	public static function deleteByIDs($localID,$remoteType,$remoteID,$variableName){
		$q = "";
		switch ($variableName) {
			case "Biomarkers":
				$q = "DELETE FROM xr_BiomarkerStudyData_Study WHERE StudyID = $localID AND BiomarkerStudyDataID = $remoteID AND StudyVar = \"Biomarkers\" LIMIT 1";
				break;
			case "BiomarkerOrganDatas":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Study WHERE StudyID = $localID AND BiomarkerOrganStudyDataID = $remoteID AND StudyVar = \"BiomarkerOrganDatas\" LIMIT 1";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM xr_Study_BiomarkerOrganData WHERE StudyID = $localID AND BiomarkerOrganDataID = $remoteID AND StudyVar = \"BiomarkerOrgans\" LIMIT 1";
				break;
			case "Publications":
				$q = "DELETE FROM xr_Study_Publication WHERE StudyID = $localID AND PublicationID = $remoteID AND StudyVar = \"Publications\" LIMIT 1";
				break;
			case "Resources":
				$q = "DELETE FROM xr_Study_Resource WHERE StudyID = $localID AND ResourceID = $remoteID AND StudyVar = \"Resources\" LIMIT 1";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
	public static function retrieve($local,$type,$parentObjects,$lazyFetch=false,$limit=0,$variableName=''){
		$objects = array();
		switch ($variableName) {
			case "Biomarkers":
				$q = "SELECT BiomarkerStudyDataID AS ID FROM xr_BiomarkerStudyData_Study WHERE StudyID = {$local->ID} AND StudyVar = \"Biomarkers\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = BiomarkerStudyData::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
					$rcount++;
				}
				break;
			case "BiomarkerOrganDatas":
				$q = "SELECT BiomarkerOrganStudyDataID AS ID FROM xr_BiomarkerOrganStudyData_Study WHERE StudyID = {$local->ID} AND StudyVar = \"BiomarkerOrganDatas\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = BiomarkerOrganStudyData::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
					$rcount++;
				}
				break;
			case "BiomarkerOrgans":
				$q = "SELECT BiomarkerOrganDataID AS ID FROM xr_Study_BiomarkerOrganData WHERE StudyID = {$local->ID} AND StudyVar = \"BiomarkerOrgans\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = BiomarkerOrganData::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
					$rcount++;
				}
				break;
			case "Publications":
				$q = "SELECT PublicationID AS ID FROM xr_Study_Publication WHERE StudyID = {$local->ID} AND StudyVar = \"Publications\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = Publication::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
					$rcount++;
				}
				break;
			case "Resources":
				$q = "SELECT ResourceID AS ID FROM xr_Study_Resource WHERE StudyID = {$local->ID} AND StudyVar = \"Resources\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = Resource::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
					$rcount++;
				}
				break;
			default:
				break;
		}
		// Use $limit to figure out what to return
		if ($limit == 1){
			return (sizeof($objects) > 0)? $objects[0] : '';
		} else {
			return $objects; // limited already during construction
		}
	}
	public static function purgeVariable($objectID,$variableName){
		$q = "";
		switch($variableName) {
			case "Biomarkers":
				$q = "DELETE FROM `xr_BiomarkerStudyData_Study` WHERE StudyID = $objectID AND StudyVar = \"Biomarkers\" ";
				break;
			case "BiomarkerOrganDatas":
				$q = "DELETE FROM `xr_BiomarkerOrganStudyData_Study` WHERE StudyID = $objectID AND StudyVar = \"BiomarkerOrganDatas\" ";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM `xr_Study_BiomarkerOrganData` WHERE StudyID = $objectID AND StudyVar = \"BiomarkerOrgans\" ";
				break;
			case "Publications":
				$q = "DELETE FROM `xr_Study_Publication` WHERE StudyID = $objectID AND StudyVar = \"Publications\" ";
				break;
			case "Resources":
				$q = "DELETE FROM `xr_Study_Resource` WHERE StudyID = $objectID AND StudyVar = \"Resources\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class StudyActions {
	public static function associateBiomarker($StudyID,$BiomarkerStudyDataID){
		StudyXref::createByIDs($StudyID,"BiomarkerStudyData",$BiomarkerStudyDataID,"Biomarkers");
	}
	public static function dissociateBiomarker($StudyID,$BiomarkerStudyDataID){
		BiomarkerStudyData::Delete($BiomarkerStudyDataID);
	}
	public static function associateBiomarkerOrgan($StudyID,$BiomarkerOrganDataID){
		StudyXref::createByIDs($StudyID,"BiomarkerOrganData",$BiomarkerOrganDataID,"BiomarkerOrgans");
	}
	public static function dissociateBiomarkerOrgan($StudyID,$BiomarkerOrganDataID){
		BiomarkerOrganData::Delete($BiomarkerOrganDataID);
	}
	public static function associateBiomarkerOrganData($StudyID,$BiomarkerOrganStudyDataID){
		StudyXref::createByIDs($StudyID,"BiomarkerOrganStudyData",$BiomarkerOrganStudyDataID,"BiomarkerOrganDatas");
	}
	public static function dissociateBiomarkerOrganData($StudyID,$BiomarkerOrganStudyDataID){
		BiomarkerOrganStudyData::Delete($BiomarkerOrganStudyDataID);
	}
	public static function associatePublication($StudyID,$PublicationID){
		StudyXref::createByIDs($StudyID,"Publication",$PublicationID,"Publications");
	}
	public static function dissociatePublication($StudyID,$PublicationID){
		StudyXref::deleteByIDs($StudyID,"Publication",$PublicationID,"Publications");
	}
	public static function associateResource($StudyID,$ResourceID){
		StudyXref::createByIDs($StudyID,"Resource",$ResourceID,"Resources");
	}
	public static function dissociateResource($StudyID,$ResourceID){
		StudyXref::deleteByIDs($StudyID,"Resource",$ResourceID,"Resources");
	}
}

?>