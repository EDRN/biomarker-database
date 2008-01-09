<?php
class Resource {

	public static function Create() {
		$vo = new voResource();
		$dao = new daoResource();
		$dao->insert(&$vo);
		$obj = new objResource();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoResource();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchResourceException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objResource();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->Biomarkers = array();
						$o->BiomarkerOrgans = array();
						$o->BiomarkerOrganStudies = array();
						$o->BiomarkerStudies = array();
						$o->Studies = array();
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->Biomarkers = ResourceXref::retrieve($o,"Biomarker",$po,$lazyFetch,$limit,"Biomarkers");
						if ($o->Biomarkers == null){$o->Biomarkers = array();}
						$o->BiomarkerOrgans = ResourceXref::retrieve($o,"BiomarkerOrganData",$po,$lazyFetch,$limit,"BiomarkerOrgans");
						if ($o->BiomarkerOrgans == null){$o->BiomarkerOrgans = array();}
						$o->BiomarkerOrganStudies = ResourceXref::retrieve($o,"BiomarkerOrganStudyData",$po,$lazyFetch,$limit,"BiomarkerOrganStudies");
						if ($o->BiomarkerOrganStudies == null){$o->BiomarkerOrganStudies = array();}
						$o->BiomarkerStudies = ResourceXref::retrieve($o,"BiomarkerStudyData",$po,$lazyFetch,$limit,"BiomarkerStudies");
						if ($o->BiomarkerStudies == null){$o->BiomarkerStudies = array();}
						$o->Studies = ResourceXref::retrieve($o,"Study",$po,$lazyFetch,$limit,"Studies");
						if ($o->Studies == null){$o->Studies = array();}
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
		$dao = new daoResource();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchResourceException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objResource;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Biomarkers = array();
			$obj->BiomarkerOrgans = array();
			$obj->BiomarkerOrganStudies = array();
			$obj->BiomarkerStudies = array();
			$obj->Studies = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Biomarkers = ResourceXref::retrieve($obj,"Biomarker",array($obj),$lazyFetch,$limit,"Biomarkers");
			if ($obj->Biomarkers == null){$obj->Biomarkers = array();}
			$obj->BiomarkerOrgans = ResourceXref::retrieve($obj,"BiomarkerOrganData",array($obj),$lazyFetch,$limit,"BiomarkerOrgans");
			if ($obj->BiomarkerOrgans == null){$obj->BiomarkerOrgans = array();}
			$obj->BiomarkerOrganStudies = ResourceXref::retrieve($obj,"BiomarkerOrganStudyData",array($obj),$lazyFetch,$limit,"BiomarkerOrganStudies");
			if ($obj->BiomarkerOrganStudies == null){$obj->BiomarkerOrganStudies = array();}
			$obj->BiomarkerStudies = ResourceXref::retrieve($obj,"BiomarkerStudyData",array($obj),$lazyFetch,$limit,"BiomarkerStudies");
			if ($obj->BiomarkerStudies == null){$obj->BiomarkerStudies = array();}
			$obj->Studies = ResourceXref::retrieve($obj,"Study",array($obj),$lazyFetch,$limit,"Studies");
			if ($obj->Studies == null){$obj->Studies = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_Resource` WHERE ResourceID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Resource` WHERE ResourceID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Resource` WHERE ResourceID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Resource` WHERE ResourceID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_Study_Resource` WHERE ResourceID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `Resource` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_Resource` WHERE ResourceID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Resource` WHERE ResourceID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Resource` WHERE ResourceID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Resource` WHERE ResourceID = $objID");
		$db->safeQuery("DELETE FROM `xr_Study_Resource` WHERE ResourceID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `Resource` WHERE ID = $objID");
	}
	public static function Exists() {
		$dao = new daoResource();
		try {
			$dao->getBy(array(),array());
		} catch (NoSuchResourceException $e){
			return false;
		}
		return true;
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoResource;
		$dao->save(&$vo);
	}
	public static function attach_Biomarker($object,$Biomarker){
		$object->Biomarkers[] = $Biomarker;
	}
	public static function attach_BiomarkerOrgan($object,$BiomarkerOrganData){
		$object->BiomarkerOrgans[] = $BiomarkerOrganData;
	}
	public static function attach_BiomarkerOrganStudie($object,$BiomarkerOrganStudyData){
		$object->BiomarkerOrganStudies[] = $BiomarkerOrganStudyData;
	}
	public static function attach_BiomarkerStudie($object,$BiomarkerStudyData){
		$object->BiomarkerStudies[] = $BiomarkerStudyData;
	}
	public static function attach_Studie($object,$Study){
		$object->Studies[] = $Study;
	}
}

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

class voResource {
	public $objId;
	public $Name;
	public $URL;

	public function toAssocArray(){
		return array(
			"objId" => $this->objId,
			"Name" => $this->Name,
			"URL" => $this->URL,
		);
	}
	public function applyAssocArray($arr) {
		if(!empty($arr['objId'])){
			$this->objId = $arr['objId'];
		}
		if(!empty($arr['Name'])){
			$this->Name = $arr['Name'];
		}
		if(!empty($arr['URL'])){
			$this->URL = $arr['URL'];
		}
	}
}

class daoResource {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `Resource` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchResourceException("No Resource found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voResource();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Resource` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voResource();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `Resource` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voResource();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Resource`"; 
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
		$q = "DELETE FROM `Resource` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `Resource` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "Name=\"$vo->Name\"" . ", ";
		$q .= "URL=\"$vo->URL\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Resource` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->Name.'","'.$vo->URL.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Resource`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->Name = $result['Name'];
		$vo->URL = $result['URL'];
	}

}

class NoSuchResourceException extends Exception {
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

class ResourceXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "Biomarkers":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Resource WHERE ResourceID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_Resource (ResourceID,BiomarkerID,ResourceVar) VALUES($localID,$remoteID,\"Biomarkers\");";
				$q1 = "UPDATE xr_Biomarker_Resource SET ResourceVar=\"{$variableName}\" WHERE ResourceID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerStudies":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Resource WHERE ResourceID=$localID AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Resource (ResourceID,BiomarkerStudyDataID,ResourceVar) VALUES($localID,$remoteID,\"BiomarkerStudies\");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Resource SET ResourceVar=\"{$variableName}\" WHERE ResourceID=$localID AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrgans":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Resource WHERE ResourceID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Resource (ResourceID,BiomarkerOrganDataID,ResourceVar) VALUES($localID,$remoteID,\"BiomarkerOrgans\");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Resource SET ResourceVar=\"{$variableName}\" WHERE ResourceID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrganStudies":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Resource WHERE ResourceID=$localID AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Resource (ResourceID,BiomarkerOrganStudyDataID,ResourceVar) VALUES($localID,$remoteID,\"BiomarkerOrganStudies\");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Resource SET ResourceVar=\"{$variableName}\" WHERE ResourceID=$localID AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "Studies":
				$q  = "SELECT COUNT(*) FROM xr_Study_Resource WHERE ResourceID=$localID AND StudyID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Study_Resource (ResourceID,StudyID,ResourceVar) VALUES($localID,$remoteID,\"Studies\");";
				$q1 = "UPDATE xr_Study_Resource SET ResourceVar=\"{$variableName}\" WHERE ResourceID=$localID AND StudyID=$remoteID LIMIT 1 ";
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
				$q = "DELETE FROM xr_Biomarker_Resource WHERE ResourceID = $localID AND BiomarkerID = $remoteID AND ResourceVar = \"Biomarkers\" LIMIT 1";
				break;
			case "BiomarkerStudies":
				$q = "DELETE FROM xr_BiomarkerStudyData_Resource WHERE ResourceID = $localID AND BiomarkerStudyDataID = $remoteID AND ResourceVar = \"BiomarkerStudies\" LIMIT 1";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM xr_BiomarkerOrganData_Resource WHERE ResourceID = $localID AND BiomarkerOrganDataID = $remoteID AND ResourceVar = \"BiomarkerOrgans\" LIMIT 1";
				break;
			case "BiomarkerOrganStudies":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Resource WHERE ResourceID = $localID AND BiomarkerOrganStudyDataID = $remoteID AND ResourceVar = \"BiomarkerOrganStudies\" LIMIT 1";
				break;
			case "Studies":
				$q = "DELETE FROM xr_Study_Resource WHERE ResourceID = $localID AND StudyID = $remoteID AND ResourceVar = \"Studies\" LIMIT 1";
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
				$q = "SELECT BiomarkerID AS ID FROM xr_Biomarker_Resource WHERE ResourceID = {$local->ID} AND ResourceVar = \"Biomarkers\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = Biomarker::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
					$rcount++;
				}
				break;
			case "BiomarkerStudies":
				$q = "SELECT BiomarkerStudyDataID AS ID FROM xr_BiomarkerStudyData_Resource WHERE ResourceID = {$local->ID} AND ResourceVar = \"BiomarkerStudies\" ";
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
			case "BiomarkerOrgans":
				$q = "SELECT BiomarkerOrganDataID AS ID FROM xr_BiomarkerOrganData_Resource WHERE ResourceID = {$local->ID} AND ResourceVar = \"BiomarkerOrgans\" ";
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
			case "BiomarkerOrganStudies":
				$q = "SELECT BiomarkerOrganStudyDataID AS ID FROM xr_BiomarkerOrganStudyData_Resource WHERE ResourceID = {$local->ID} AND ResourceVar = \"BiomarkerOrganStudies\" ";
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
			case "Studies":
				$q = "SELECT StudyID AS ID FROM xr_Study_Resource WHERE ResourceID = {$local->ID} AND ResourceVar = \"Studies\" ";
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
				$q = "DELETE FROM `xr_Biomarker_Resource` WHERE ResourceID = $objectID AND ResourceVar = \"Biomarkers\" ";
				break;
			case "BiomarkerStudies":
				$q = "DELETE FROM `xr_BiomarkerStudyData_Resource` WHERE ResourceID = $objectID AND ResourceVar = \"BiomarkerStudies\" ";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM `xr_BiomarkerOrganData_Resource` WHERE ResourceID = $objectID AND ResourceVar = \"BiomarkerOrgans\" ";
				break;
			case "BiomarkerOrganStudies":
				$q = "DELETE FROM `xr_BiomarkerOrganStudyData_Resource` WHERE ResourceID = $objectID AND ResourceVar = \"BiomarkerOrganStudies\" ";
				break;
			case "Studies":
				$q = "DELETE FROM `xr_Study_Resource` WHERE ResourceID = $objectID AND ResourceVar = \"Studies\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class ResourceActions {
	public static function associateBiomarker($ResourceID,$BiomarkerID){
		ResourceXref::createByIDs($ResourceID,"Biomarker",$BiomarkerID,"Biomarkers");
	}
	public static function dissociateBiomarker($ResourceID,$BiomarkerID){
		ResourceXref::deleteByIDs($ResourceID,"Biomarker",$BiomarkerID,"Biomarkers");
	}
	public static function associateBiomarkerOrgan($ResourceID,$BiomarkerOrganDataID){
		ResourceXref::createByIDs($ResourceID,"BiomarkerOrganData",$BiomarkerOrganDataID,"BiomarkerOrgans");
	}
	public static function dissociateBiomarkerOrgan($ResourceID,$BiomarkerOrganDataID){
		ResourceXref::deleteByIDs($ResourceID,"BiomarkerOrganData",$BiomarkerOrganDataID,"BiomarkerOrgans");
	}
	public static function associateBiomarkerOrganStudy($ResourceID,$BiomarkerOrganStudyDataID){
		ResourceXref::createByIDs($ResourceID,"BiomarkerOrganStudyData",$BiomarkerOrganStudyDataID,"BiomarkerOrganStudies");
	}
	public static function dissociateBiomarkerOrganStudy($ResourceID,$BiomarkerOrganStudyDataID){
		ResourceXref::deleteByIDs($ResourceID,"BiomarkerOrganStudyData",$BiomarkerOrganStudyDataID,"BiomarkerOrganStudies");
	}
	public static function associateBiomarkerStudy($ResourceID,$BiomarkerStudyDataID){
		ResourceXref::createByIDs($ResourceID,"BiomarkerStudyData",$BiomarkerStudyDataID,"BiomarkerStudies");
	}
	public static function dissociateBiomarkerStudy($ResourceID,$BiomarkerStudyDataID){
		ResourceXref::deleteByIDs($ResourceID,"BiomarkerStudyData",$BiomarkerStudyDataID,"BiomarkerStudies");
	}
	public static function associateStudy($ResourceID,$StudyID){
		ResourceXref::createByIDs($ResourceID,"Study",$StudyID,"Studies");
	}
	public static function dissociateStudy($ResourceID,$StudyID){
		ResourceXref::deleteByIDs($ResourceID,"Study",$StudyID,"Studies");
	}
}

?>