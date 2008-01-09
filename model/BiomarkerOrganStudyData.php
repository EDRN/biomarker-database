<?php
class BiomarkerOrganStudyData {

	public static function Create() {
		$vo = new voBiomarkerOrganStudyData();
		$dao = new daoBiomarkerOrganStudyData();
		$dao->insert(&$vo);
		$obj = new objBiomarkerOrganStudyData();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoBiomarkerOrganStudyData();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchBiomarkerOrganStudyDataException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objBiomarkerOrganStudyData();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->Study = '';
						$o->BiomarkerOrganData = '';
						$o->Publications = array();
						$o->Resources = array();
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->Study = BiomarkerOrganStudyDataXref::retrieve($o,"Study",$po,$lazyFetch,$limit,"Study");
						if ($o->Study == null){$o->Study = '';}
						$o->BiomarkerOrganData = BiomarkerOrganStudyDataXref::retrieve($o,"BiomarkerOrganData",$po,$lazyFetch,$limit,"BiomarkerOrganData");
						if ($o->BiomarkerOrganData == null){$o->BiomarkerOrganData = '';}
						$o->Publications = BiomarkerOrganStudyDataXref::retrieve($o,"Publication",$po,$lazyFetch,$limit,"Publications");
						if ($o->Publications == null){$o->Publications = array();}
						$o->Resources = BiomarkerOrganStudyDataXref::retrieve($o,"Resource",$po,$lazyFetch,$limit,"Resources");
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
		$dao = new daoBiomarkerOrganStudyData();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchBiomarkerOrganStudyDataException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objBiomarkerOrganStudyData;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Study = '';
			$obj->BiomarkerOrganData = '';
			$obj->Publications = array();
			$obj->Resources = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Study = BiomarkerOrganStudyDataXref::retrieve($obj,"Study",array($obj),$lazyFetch,1,"Study");
			if ($obj->Study == null){$obj->Study = '';}
			$obj->BiomarkerOrganData = BiomarkerOrganStudyDataXref::retrieve($obj,"BiomarkerOrganData",array($obj),$lazyFetch,1,"BiomarkerOrganData");
			if ($obj->BiomarkerOrganData == null){$obj->BiomarkerOrganData = '';}
			$obj->Publications = BiomarkerOrganStudyDataXref::retrieve($obj,"Publication",array($obj),$lazyFetch,$limit,"Publications");
			if ($obj->Publications == null){$obj->Publications = array();}
			$obj->Resources = BiomarkerOrganStudyDataXref::retrieve($obj,"Resource",array($obj),$lazyFetch,$limit,"Resources");
			if ($obj->Resources == null){$obj->Resources = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_BiomarkerOrganStudyData` WHERE BiomarkerOrganStudyDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Study` WHERE BiomarkerOrganStudyDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Publication` WHERE BiomarkerOrganStudyDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Resource` WHERE BiomarkerOrganStudyDataID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `BiomarkerOrganStudyData` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_BiomarkerOrganStudyData` WHERE BiomarkerOrganStudyDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Study` WHERE BiomarkerOrganStudyDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Publication` WHERE BiomarkerOrganStudyDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Resource` WHERE BiomarkerOrganStudyDataID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `BiomarkerOrganStudyData` WHERE ID = $objID");
	}
	public static function Exists() {
		$dao = new daoBiomarkerOrganStudyData();
		try {
			$dao->getBy(array(),array());
		} catch (NoSuchBiomarkerOrganStudyDataException $e){
			return false;
		}
		return true;
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoBiomarkerOrganStudyData;
		$dao->save(&$vo);
	}
	public static function attach_Study($object,$Study){
		$object->Study = $Study;
	}
	public static function attach_BiomarkerOrganData($object,$BiomarkerOrganData){
		$object->BiomarkerOrganData = $BiomarkerOrganData;
	}
	public static function attach_Publication($object,$Publication){
		$object->Publications[] = $Publication;
	}
	public static function attach_Resource($object,$Resource){
		$object->Resources[] = $Resource;
	}
}

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

class voBiomarkerOrganStudyData {
	public $objId;
	public $Sensitivity;
	public $Specificity;
	public $PPV;
	public $NPV;
	public $Assay;
	public $Technology;

	public function toAssocArray(){
		return array(
			"objId" => $this->objId,
			"Sensitivity" => $this->Sensitivity,
			"Specificity" => $this->Specificity,
			"PPV" => $this->PPV,
			"NPV" => $this->NPV,
			"Assay" => $this->Assay,
			"Technology" => $this->Technology,
		);
	}
	public function applyAssocArray($arr) {
		if(!empty($arr['objId'])){
			$this->objId = $arr['objId'];
		}
		if(!empty($arr['Sensitivity'])){
			$this->Sensitivity = $arr['Sensitivity'];
		}
		if(!empty($arr['Specificity'])){
			$this->Specificity = $arr['Specificity'];
		}
		if(!empty($arr['PPV'])){
			$this->PPV = $arr['PPV'];
		}
		if(!empty($arr['NPV'])){
			$this->NPV = $arr['NPV'];
		}
		if(!empty($arr['Assay'])){
			$this->Assay = $arr['Assay'];
		}
		if(!empty($arr['Technology'])){
			$this->Technology = $arr['Technology'];
		}
	}
}

class daoBiomarkerOrganStudyData {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `BiomarkerOrganStudyData` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchBiomarkerOrganStudyDataException("No BiomarkerOrganStudyData found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerOrganStudyData();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `BiomarkerOrganStudyData` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerOrganStudyData();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `BiomarkerOrganStudyData` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerOrganStudyData();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `BiomarkerOrganStudyData`"; 
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
		$q = "DELETE FROM `BiomarkerOrganStudyData` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `BiomarkerOrganStudyData` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "Sensitivity=\"$vo->Sensitivity\"" . ", ";
		$q .= "Specificity=\"$vo->Specificity\"" . ", ";
		$q .= "PPV=\"$vo->PPV\"" . ", ";
		$q .= "NPV=\"$vo->NPV\"" . ", ";
		$q .= "Assay=\"$vo->Assay\"" . ", ";
		$q .= "Technology=\"$vo->Technology\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `BiomarkerOrganStudyData` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->Sensitivity.'","'.$vo->Specificity.'","'.$vo->PPV.'","'.$vo->NPV.'","'.$vo->Assay.'","'.$vo->Technology.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `BiomarkerOrganStudyData`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->Sensitivity = $result['Sensitivity'];
		$vo->Specificity = $result['Specificity'];
		$vo->PPV = $result['PPV'];
		$vo->NPV = $result['NPV'];
		$vo->Assay = $result['Assay'];
		$vo->Technology = $result['Technology'];
	}

}

class NoSuchBiomarkerOrganStudyDataException extends Exception {
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

class BiomarkerOrganStudyDataXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "BiomarkerOrganData":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_BiomarkerOrganStudyData (BiomarkerOrganStudyDataID,BiomarkerOrganDataID,BiomarkerOrganStudyDataVar) VALUES($localID,$remoteID,\"BiomarkerOrganData\");";
				$q1 = "UPDATE xr_BiomarkerOrganData_BiomarkerOrganStudyData SET BiomarkerOrganStudyDataVar=\"{$variableName}\" WHERE BiomarkerOrganStudyDataID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Study":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID=$localID AND StudyID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Study (BiomarkerOrganStudyDataID,StudyID,BiomarkerOrganStudyDataVar) VALUES($localID,$remoteID,\"Study\");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Study SET BiomarkerOrganStudyDataVar=\"{$variableName}\" WHERE BiomarkerOrganStudyDataID=$localID AND StudyID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Publication (BiomarkerOrganStudyDataID,PublicationID,BiomarkerOrganStudyDataVar) VALUES($localID,$remoteID,\"Publications\");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Publication SET BiomarkerOrganStudyDataVar=\"{$variableName}\" WHERE BiomarkerOrganStudyDataID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID=$localID AND ResourceID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Resource (BiomarkerOrganStudyDataID,ResourceID,BiomarkerOrganStudyDataVar) VALUES($localID,$remoteID,\"Resources\");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Resource SET BiomarkerOrganStudyDataVar=\"{$variableName}\" WHERE BiomarkerOrganStudyDataID=$localID AND ResourceID=$remoteID LIMIT 1 ";
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
			case "BiomarkerOrganData":
				$q = "DELETE FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID = $localID AND BiomarkerOrganDataID = $remoteID AND BiomarkerOrganStudyDataVar = \"BiomarkerOrganData\" LIMIT 1";
				break;
			case "Study":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID = $localID AND StudyID = $remoteID AND BiomarkerOrganStudyDataVar = \"Study\" LIMIT 1";
				break;
			case "Publications":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID = $localID AND PublicationID = $remoteID AND BiomarkerOrganStudyDataVar = \"Publications\" LIMIT 1";
				break;
			case "Resources":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID = $localID AND ResourceID = $remoteID AND BiomarkerOrganStudyDataVar = \"Resources\" LIMIT 1";
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
			case "BiomarkerOrganData":
				$q = "SELECT BiomarkerOrganDataID AS ID FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID = {$local->ID} AND BiomarkerOrganStudyDataVar = \"BiomarkerOrganData\" ";
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
			case "Study":
				$q = "SELECT StudyID AS ID FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID = {$local->ID} AND BiomarkerOrganStudyDataVar = \"Study\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = Study::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
					$rcount++;
				}
				break;
			case "Publications":
				$q = "SELECT PublicationID AS ID FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID = {$local->ID} AND BiomarkerOrganStudyDataVar = \"Publications\" ";
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
				$q = "SELECT ResourceID AS ID FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID = {$local->ID} AND BiomarkerOrganStudyDataVar = \"Resources\" ";
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
			case "BiomarkerOrganData":
				$q = "DELETE FROM `xr_BiomarkerOrganData_BiomarkerOrganStudyData` WHERE BiomarkerOrganStudyDataID = $objectID AND BiomarkerOrganStudyDataVar = \"BiomarkerOrganData\" ";
				break;
			case "Study":
				$q = "DELETE FROM `xr_BiomarkerOrganStudyData_Study` WHERE BiomarkerOrganStudyDataID = $objectID AND BiomarkerOrganStudyDataVar = \"Study\" ";
				break;
			case "Publications":
				$q = "DELETE FROM `xr_BiomarkerOrganStudyData_Publication` WHERE BiomarkerOrganStudyDataID = $objectID AND BiomarkerOrganStudyDataVar = \"Publications\" ";
				break;
			case "Resources":
				$q = "DELETE FROM `xr_BiomarkerOrganStudyData_Resource` WHERE BiomarkerOrganStudyDataID = $objectID AND BiomarkerOrganStudyDataVar = \"Resources\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class BiomarkerOrganStudyDataActions {
	public static function associateStudy($BiomarkerOrganStudyDataID,$StudyID){
		BiomarkerOrganStudyDataXref::purgeVariable($BiomarkerOrganStudyDataID,"Study");
		BiomarkerOrganStudyDataXref::createByIDs($BiomarkerOrganStudyDataID,"Study",$StudyID,"Study");
	}
	public static function dissociateStudy($BiomarkerOrganStudyDataID,$StudyID){
		BiomarkerOrganStudyDataXref::deleteByIDs($BiomarkerOrganStudyDataID,"Study",$StudyID,"Study");
	}
	public static function associateBiomarkerOrganData($BiomarkerOrganStudyDataID,$BiomarkerOrganDataID){
		BiomarkerOrganStudyDataXref::purgeVariable($BiomarkerOrganStudyDataID,"BiomarkerOrganData");
		BiomarkerOrganStudyDataXref::createByIDs($BiomarkerOrganStudyDataID,"BiomarkerOrganData",$BiomarkerOrganDataID,"BiomarkerOrganData");
	}
	public static function dissociateBiomarkerOrganData($BiomarkerOrganStudyDataID,$BiomarkerOrganDataID){
		BiomarkerOrganStudyDataXref::deleteByIDs($BiomarkerOrganStudyDataID,"BiomarkerOrganData",$BiomarkerOrganDataID,"BiomarkerOrganData");
	}
	public static function associatePublication($BiomarkerOrganStudyDataID,$PublicationID){
		BiomarkerOrganStudyDataXref::createByIDs($BiomarkerOrganStudyDataID,"Publication",$PublicationID,"Publications");
	}
	public static function dissociatePublication($BiomarkerOrganStudyDataID,$PublicationID){
		BiomarkerOrganStudyDataXref::deleteByIDs($BiomarkerOrganStudyDataID,"Publication",$PublicationID,"Publications");
	}
	public static function associateResource($BiomarkerOrganStudyDataID,$ResourceID){
		BiomarkerOrganStudyDataXref::createByIDs($BiomarkerOrganStudyDataID,"Resource",$ResourceID,"Resources");
	}
	public static function dissociateResource($BiomarkerOrganStudyDataID,$ResourceID){
		BiomarkerOrganStudyDataXref::deleteByIDs($BiomarkerOrganStudyDataID,"Resource",$ResourceID,"Resources");
	}
}

?>