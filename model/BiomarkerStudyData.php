<?php
class BiomarkerStudyData {

	public static function Create() {
		$vo = new voBiomarkerStudyData();
		$dao = new daoBiomarkerStudyData();
		$dao->insert(&$vo);
		$obj = new objBiomarkerStudyData();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoBiomarkerStudyData();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchBiomarkerStudyDataException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objBiomarkerStudyData();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->Study = '';
						$o->Biomarker = '';
						$o->Publications = array();
						$o->Resources = array();
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->Study = BiomarkerStudyDataXref::retrieve($o,"Study",$po,$lazyFetch,$limit,"Study");
						if ($o->Study == null){$o->Study = '';}
						$o->Biomarker = BiomarkerStudyDataXref::retrieve($o,"Biomarker",$po,$lazyFetch,$limit,"Biomarker");
						if ($o->Biomarker == null){$o->Biomarker = '';}
						$o->Publications = BiomarkerStudyDataXref::retrieve($o,"Publication",$po,$lazyFetch,$limit,"Publications");
						if ($o->Publications == null){$o->Publications = array();}
						$o->Resources = BiomarkerStudyDataXref::retrieve($o,"Resource",$po,$lazyFetch,$limit,"Resources");
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
		$dao = new daoBiomarkerStudyData();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchBiomarkerStudyDataException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objBiomarkerStudyData;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Study = '';
			$obj->Biomarker = '';
			$obj->Publications = array();
			$obj->Resources = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Study = BiomarkerStudyDataXref::retrieve($obj,"Study",array($obj),$lazyFetch,1,"Study");
			if ($obj->Study == null){$obj->Study = '';}
			$obj->Biomarker = BiomarkerStudyDataXref::retrieve($obj,"Biomarker",array($obj),$lazyFetch,1,"Biomarker");
			if ($obj->Biomarker == null){$obj->Biomarker = '';}
			$obj->Publications = BiomarkerStudyDataXref::retrieve($obj,"Publication",array($obj),$lazyFetch,$limit,"Publications");
			if ($obj->Publications == null){$obj->Publications = array();}
			$obj->Resources = BiomarkerStudyDataXref::retrieve($obj,"Resource",array($obj),$lazyFetch,$limit,"Resources");
			if ($obj->Resources == null){$obj->Resources = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerStudyData` WHERE BiomarkerStudyDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Study` WHERE BiomarkerStudyDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Publication` WHERE BiomarkerStudyDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Resource` WHERE BiomarkerStudyDataID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `BiomarkerStudyData` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerStudyData` WHERE BiomarkerStudyDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Study` WHERE BiomarkerStudyDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Publication` WHERE BiomarkerStudyDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Resource` WHERE BiomarkerStudyDataID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `BiomarkerStudyData` WHERE ID = $objID");
	}
	public static function Exists() {
		$dao = new daoBiomarkerStudyData();
		try {
			$dao->getBy(array(),array());
		} catch (NoSuchBiomarkerStudyDataException $e){
			return false;
		}
		return true;
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoBiomarkerStudyData;
		$dao->save(&$vo);
	}
	public static function attach_Study($object,$Study){
		$object->Study = $Study;
	}
	public static function attach_Biomarker($object,$Biomarker){
		$object->Biomarker = $Biomarker;
	}
	public static function attach_Publication($object,$Publication){
		$object->Publications[] = $Publication;
	}
	public static function attach_Resource($object,$Resource){
		$object->Resources[] = $Resource;
	}
}

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

class voBiomarkerStudyData {
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

class daoBiomarkerStudyData {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `BiomarkerStudyData` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchBiomarkerStudyDataException("No BiomarkerStudyData found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerStudyData();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `BiomarkerStudyData` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerStudyData();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `BiomarkerStudyData` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerStudyData();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `BiomarkerStudyData`"; 
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
		$q = "DELETE FROM `BiomarkerStudyData` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `BiomarkerStudyData` SET ";
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
		$q = "INSERT INTO `BiomarkerStudyData` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->Sensitivity.'","'.$vo->Specificity.'","'.$vo->PPV.'","'.$vo->NPV.'","'.$vo->Assay.'","'.$vo->Technology.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `BiomarkerStudyData`");
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

class NoSuchBiomarkerStudyDataException extends Exception {
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

class BiomarkerStudyDataXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "Biomarker":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerStudyData (BiomarkerStudyDataID,BiomarkerID,BiomarkerStudyDataVar) VALUES($localID,$remoteID,\"Biomarker\");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerStudyData SET BiomarkerStudyDataVar=\"{$variableName}\" WHERE BiomarkerStudyDataID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "Study":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID=$localID AND StudyID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Study (BiomarkerStudyDataID,StudyID,BiomarkerStudyDataVar) VALUES($localID,$remoteID,\"Study\");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Study SET BiomarkerStudyDataVar=\"{$variableName}\" WHERE BiomarkerStudyDataID=$localID AND StudyID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Publication (BiomarkerStudyDataID,PublicationID,BiomarkerStudyDataVar) VALUES($localID,$remoteID,\"Publications\");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Publication SET BiomarkerStudyDataVar=\"{$variableName}\" WHERE BiomarkerStudyDataID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID=$localID AND ResourceID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Resource (BiomarkerStudyDataID,ResourceID,BiomarkerStudyDataVar) VALUES($localID,$remoteID,\"Resources\");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Resource SET BiomarkerStudyDataVar=\"{$variableName}\" WHERE BiomarkerStudyDataID=$localID AND ResourceID=$remoteID LIMIT 1 ";
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
			case "Biomarker":
				$q = "DELETE FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID = $localID AND BiomarkerID = $remoteID AND BiomarkerStudyDataVar = \"Biomarker\" LIMIT 1";
				break;
			case "Study":
				$q = "DELETE FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID = $localID AND StudyID = $remoteID AND BiomarkerStudyDataVar = \"Study\" LIMIT 1";
				break;
			case "Publications":
				$q = "DELETE FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID = $localID AND PublicationID = $remoteID AND BiomarkerStudyDataVar = \"Publications\" LIMIT 1";
				break;
			case "Resources":
				$q = "DELETE FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID = $localID AND ResourceID = $remoteID AND BiomarkerStudyDataVar = \"Resources\" LIMIT 1";
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
			case "Biomarker":
				$q = "SELECT BiomarkerID AS ID FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID = {$local->ID} AND BiomarkerStudyDataVar = \"Biomarker\" ";
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
			case "Study":
				$q = "SELECT StudyID AS ID FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID = {$local->ID} AND BiomarkerStudyDataVar = \"Study\" ";
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
				$q = "SELECT PublicationID AS ID FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID = {$local->ID} AND BiomarkerStudyDataVar = \"Publications\" ";
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
				$q = "SELECT ResourceID AS ID FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID = {$local->ID} AND BiomarkerStudyDataVar = \"Resources\" ";
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
			case "Biomarker":
				$q = "DELETE FROM `xr_Biomarker_BiomarkerStudyData` WHERE BiomarkerStudyDataID = $objectID AND BiomarkerStudyDataVar = \"Biomarker\" ";
				break;
			case "Study":
				$q = "DELETE FROM `xr_BiomarkerStudyData_Study` WHERE BiomarkerStudyDataID = $objectID AND BiomarkerStudyDataVar = \"Study\" ";
				break;
			case "Publications":
				$q = "DELETE FROM `xr_BiomarkerStudyData_Publication` WHERE BiomarkerStudyDataID = $objectID AND BiomarkerStudyDataVar = \"Publications\" ";
				break;
			case "Resources":
				$q = "DELETE FROM `xr_BiomarkerStudyData_Resource` WHERE BiomarkerStudyDataID = $objectID AND BiomarkerStudyDataVar = \"Resources\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class BiomarkerStudyDataActions {
	public static function associateStudy($BiomarkerStudyDataID,$StudyID){
		BiomarkerStudyDataXref::purgeVariable($BiomarkerStudyDataID,"Study");
		BiomarkerStudyDataXref::createByIDs($BiomarkerStudyDataID,"Study",$StudyID,"Study");
	}
	public static function dissociateStudy($BiomarkerStudyDataID,$StudyID){
		BiomarkerStudyDataXref::deleteByIDs($BiomarkerStudyDataID,"Study",$StudyID,"Study");
	}
	public static function associateBiomarker($BiomarkerStudyDataID,$BiomarkerID){
		BiomarkerStudyDataXref::purgeVariable($BiomarkerStudyDataID,"Biomarker");
		BiomarkerStudyDataXref::createByIDs($BiomarkerStudyDataID,"Biomarker",$BiomarkerID,"Biomarker");
	}
	public static function dissociateBiomarker($BiomarkerStudyDataID,$BiomarkerID){
		BiomarkerStudyDataXref::deleteByIDs($BiomarkerStudyDataID,"Biomarker",$BiomarkerID,"Biomarker");
	}
	public static function associatePublication($BiomarkerStudyDataID,$PublicationID){
		BiomarkerStudyDataXref::createByIDs($BiomarkerStudyDataID,"Publication",$PublicationID,"Publications");
	}
	public static function dissociatePublication($BiomarkerStudyDataID,$PublicationID){
		BiomarkerStudyDataXref::deleteByIDs($BiomarkerStudyDataID,"Publication",$PublicationID,"Publications");
	}
	public static function associateResource($BiomarkerStudyDataID,$ResourceID){
		BiomarkerStudyDataXref::createByIDs($BiomarkerStudyDataID,"Resource",$ResourceID,"Resources");
	}
	public static function dissociateResource($BiomarkerStudyDataID,$ResourceID){
		BiomarkerStudyDataXref::deleteByIDs($BiomarkerStudyDataID,"Resource",$ResourceID,"Resources");
	}
}

?>