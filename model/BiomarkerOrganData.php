<?php
class BiomarkerOrganData {

	public static function Create() {
		$vo = new voBiomarkerOrganData();
		$dao = new daoBiomarkerOrganData();
		$dao->insert(&$vo);
		$obj = new objBiomarkerOrganData();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoBiomarkerOrganData();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchBiomarkerOrganDataException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objBiomarkerOrganData();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->Organ = '';
						$o->Biomarker = '';
						$o->Resources = array();
						$o->Publications = array();
						$o->StudyDatas = array();
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->Organ = BiomarkerOrganDataXref::retrieve($o,"Organ",$po,$lazyFetch,$limit,"Organ");
						if ($o->Organ == null){$o->Organ = '';}
						$o->Biomarker = BiomarkerOrganDataXref::retrieve($o,"Biomarker",$po,$lazyFetch,$limit,"Biomarker");
						if ($o->Biomarker == null){$o->Biomarker = '';}
						$o->Resources = BiomarkerOrganDataXref::retrieve($o,"Resource",$po,$lazyFetch,$limit,"Resources");
						if ($o->Resources == null){$o->Resources = array();}
						$o->Publications = BiomarkerOrganDataXref::retrieve($o,"Publication",$po,$lazyFetch,$limit,"Publications");
						if ($o->Publications == null){$o->Publications = array();}
						$o->StudyDatas = BiomarkerOrganDataXref::retrieve($o,"BiomarkerOrganStudyData",$po,$lazyFetch,$limit,"StudyDatas");
						if ($o->StudyDatas == null){$o->StudyDatas = array();}
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
		$dao = new daoBiomarkerOrganData();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchBiomarkerOrganDataException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objBiomarkerOrganData;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Organ = '';
			$obj->Biomarker = '';
			$obj->Resources = array();
			$obj->Publications = array();
			$obj->StudyDatas = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Organ = BiomarkerOrganDataXref::retrieve($obj,"Organ",array($obj),$lazyFetch,1,"Organ");
			if ($obj->Organ == null){$obj->Organ = '';}
			$obj->Biomarker = BiomarkerOrganDataXref::retrieve($obj,"Biomarker",array($obj),$lazyFetch,1,"Biomarker");
			if ($obj->Biomarker == null){$obj->Biomarker = '';}
			$obj->Resources = BiomarkerOrganDataXref::retrieve($obj,"Resource",array($obj),$lazyFetch,$limit,"Resources");
			if ($obj->Resources == null){$obj->Resources = array();}
			$obj->Publications = BiomarkerOrganDataXref::retrieve($obj,"Publication",array($obj),$lazyFetch,$limit,"Publications");
			if ($obj->Publications == null){$obj->Publications = array();}
			$obj->StudyDatas = BiomarkerOrganDataXref::retrieve($obj,"BiomarkerOrganStudyData",array($obj),$lazyFetch,$limit,"StudyDatas");
			if ($obj->StudyDatas == null){$obj->StudyDatas = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerOrganData` WHERE BiomarkerOrganDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Organ` WHERE BiomarkerOrganDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Resource` WHERE BiomarkerOrganDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Publication` WHERE BiomarkerOrganDataID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_BiomarkerOrganStudyData` WHERE BiomarkerOrganDataID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `BiomarkerOrganData` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerOrganData` WHERE BiomarkerOrganDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Organ` WHERE BiomarkerOrganDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Resource` WHERE BiomarkerOrganDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Publication` WHERE BiomarkerOrganDataID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_BiomarkerOrganStudyData` WHERE BiomarkerOrganDataID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `BiomarkerOrganData` WHERE ID = $objID");
	}
	public static function Exists() {
		$dao = new daoBiomarkerOrganData();
		try {
			$dao->getBy(array(),array());
		} catch (NoSuchBiomarkerOrganDataException $e){
			return false;
		}
		return true;
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoBiomarkerOrganData;
		$dao->save(&$vo);
	}
	public static function attach_Organ($object,$Organ){
		$object->Organ = $Organ;
	}
	public static function attach_Biomarker($object,$Biomarker){
		$object->Biomarker = $Biomarker;
	}
	public static function attach_Resource($object,$Resource){
		$object->Resources[] = $Resource;
	}
	public static function attach_Publication($object,$Publication){
		$object->Publications[] = $Publication;
	}
	public static function attach_StudyData($object,$BiomarkerOrganStudyData){
		$object->StudyDatas[] = $BiomarkerOrganStudyData;
	}
}

class BiomarkerOrganDataVars {
	const BIO_OBJID = "objId";
	const BIO_SENSITIVITYMIN = "SensitivityMin";
	const BIO_SENSITIVITYMAX = "SensitivityMax";
	const BIO_SENSITIVITYCOMMENT = "SensitivityComment";
	const BIO_SPECIFICITYMIN = "SpecificityMin";
	const BIO_SPECIFICITYMAX = "SpecificityMax";
	const BIO_SPECIFICITYCOMMENT = "SpecificityComment";
	const BIO_PPVMIN = "PPVMin";
	const BIO_PPVMAX = "PPVMax";
	const BIO_PPVCOMMENT = "PPVComment";
	const BIO_NPVMIN = "NPVMin";
	const BIO_NPVMAX = "NPVMax";
	const BIO_NPVCOMMENT = "NPVComment";
	const BIO_ORGAN = "Organ";
	const BIO_BIOMARKER = "Biomarker";
	const BIO_RESOURCES = "Resources";
	const BIO_PUBLICATIONS = "Publications";
	const BIO_STUDYDATAS = "StudyDatas";
}

class objBiomarkerOrganData {

	const _TYPE = "BiomarkerOrganData";
	private $XPress;
	public $objId = '';
	public $SensitivityMin = '';
	public $SensitivityMax = '';
	public $SensitivityComment = '';
	public $SpecificityMin = '';
	public $SpecificityMax = '';
	public $SpecificityComment = '';
	public $PPVMin = '';
	public $PPVMax = '';
	public $PPVComment = '';
	public $NPVMin = '';
	public $NPVMax = '';
	public $NPVComment = '';
	public $Organ = '';
	public $Biomarker = '';
	public $Resources = array();
	public $Publications = array();
	public $StudyDatas = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type BiomarkerOrganData<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		$this->objId = $objId;
		$q = "SELECT * FROM `BiomarkerOrganData` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->SensitivityMin = $result['SensitivityMin'];
			$this->SensitivityMax = $result['SensitivityMax'];
			$this->SensitivityComment = $result['SensitivityComment'];
			$this->SpecificityMin = $result['SpecificityMin'];
			$this->SpecificityMax = $result['SpecificityMax'];
			$this->SpecificityComment = $result['SpecificityComment'];
			$this->PPVMin = $result['PPVMin'];
			$this->PPVMax = $result['PPVMax'];
			$this->PPVComment = $result['PPVComment'];
			$this->NPVMin = $result['NPVMin'];
			$this->NPVMax = $result['NPVMax'];
			$this->NPVComment = $result['NPVComment'];
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
		$this->SensitivityMin = '';
		$this->SensitivityMax = '';
		$this->SensitivityComment = '';
		$this->SpecificityMin = '';
		$this->SpecificityMax = '';
		$this->SpecificityComment = '';
		$this->PPVMin = '';
		$this->PPVMax = '';
		$this->PPVComment = '';
		$this->NPVMin = '';
		$this->NPVMax = '';
		$this->NPVComment = '';
		$this->Organ = '';
		$this->Biomarker = '';
		$this->Resources = array();
		$this->Publications = array();
		$this->StudyDatas = array();
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getSensitivityMin() {
		 return $this->SensitivityMin;
	}
	public function getSensitivityMax() {
		 return $this->SensitivityMax;
	}
	public function getSensitivityComment() {
		 return $this->SensitivityComment;
	}
	public function getSpecificityMin() {
		 return $this->SpecificityMin;
	}
	public function getSpecificityMax() {
		 return $this->SpecificityMax;
	}
	public function getSpecificityComment() {
		 return $this->SpecificityComment;
	}
	public function getPPVMin() {
		 return $this->PPVMin;
	}
	public function getPPVMax() {
		 return $this->PPVMax;
	}
	public function getPPVComment() {
		 return $this->PPVComment;
	}
	public function getNPVMin() {
		 return $this->NPVMin;
	}
	public function getNPVMax() {
		 return $this->NPVMax;
	}
	public function getNPVComment() {
		 return $this->NPVComment;
	}
	public function getOrgan() {
		 return $this->Organ;
	}
	public function getBiomarker() {
		 return $this->Biomarker;
	}
	public function getResources() {
		 return $this->Resources;
	}
	public function getPublications() {
		 return $this->Publications;
	}
	public function getStudyDatas() {
		 return $this->StudyDatas;
	}

	// Mutator Functions 
	public function setObjId($value,$bSave = true) {
		$this->objId = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSensitivityMin($value,$bSave = true) {
		$this->SensitivityMin = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSensitivityMax($value,$bSave = true) {
		$this->SensitivityMax = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSensitivityComment($value,$bSave = true) {
		$this->SensitivityComment = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSpecificityMin($value,$bSave = true) {
		$this->SpecificityMin = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSpecificityMax($value,$bSave = true) {
		$this->SpecificityMax = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSpecificityComment($value,$bSave = true) {
		$this->SpecificityComment = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setPPVMin($value,$bSave = true) {
		$this->PPVMin = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setPPVMax($value,$bSave = true) {
		$this->PPVMax = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setPPVComment($value,$bSave = true) {
		$this->PPVComment = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setNPVMin($value,$bSave = true) {
		$this->NPVMin = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setNPVMax($value,$bSave = true) {
		$this->NPVMax = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setNPVComment($value,$bSave = true) {
		$this->NPVComment = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function create($OrganId,$BiomarkerId){
		$this->save();
		$this->link("Organ",$OrganId,"OrganDatas");
		$this->link("Biomarker",$BiomarkerId,"OrganDatas");
	}
	public function inflate($parentObjects = array()) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "Biomarker":
		$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"Biomarker\" ";
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
		// Inflate "Organ":
		$q = "SELECT OrganID AS objId FROM xr_BiomarkerOrganData_Organ WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"Organ\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objOrgan($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Organ = $obj;
			}
			$rcount++;
		}
		// Inflate "Resources":
		$q = "SELECT ResourceID AS objId FROM xr_BiomarkerOrganData_Resource WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"Resources\" ";
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
		// Inflate "Publications":
		$q = "SELECT PublicationID AS objId FROM xr_BiomarkerOrganData_Publication WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"Publications\" ";
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
		// Inflate "StudyDatas":
		$q = "SELECT BiomarkerOrganStudyDataID AS objId FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"StudyDatas\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerOrganStudyData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->StudyDatas[] = $obj;
			}
			$rcount++;
		}
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `BiomarkerOrganData` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->SensitivityMin.'","'.$this->SensitivityMax.'","'.$this->SensitivityComment.'","'.$this->SpecificityMin.'","'.$this->SpecificityMax.'","'.$this->SpecificityComment.'","'.$this->PPVMin.'","'.$this->PPVMax.'","'.$this->PPVComment.'","'.$this->NPVMin.'","'.$this->NPVMax.'","'.$this->NPVComment.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `BiomarkerOrganData`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `BiomarkerOrganData` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`SensitivityMin`=\"$this->SensitivityMin\","; 
			$q .= "`SensitivityMax`=\"$this->SensitivityMax\","; 
			$q .= "`SensitivityComment`=\"$this->SensitivityComment\","; 
			$q .= "`SpecificityMin`=\"$this->SpecificityMin\","; 
			$q .= "`SpecificityMax`=\"$this->SpecificityMax\","; 
			$q .= "`SpecificityComment`=\"$this->SpecificityComment\","; 
			$q .= "`PPVMin`=\"$this->PPVMin\","; 
			$q .= "`PPVMax`=\"$this->PPVMax\","; 
			$q .= "`PPVComment`=\"$this->PPVComment\","; 
			$q .= "`NPVMin`=\"$this->NPVMin\","; 
			$q .= "`NPVMax`=\"$this->NPVMax\","; 
			$q .= "`NPVComment`=\"$this->NPVComment\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerOrganDataVars::BIO_ORGAN);
		$this->unlink(BiomarkerOrganDataVars::BIO_BIOMARKER);
		$this->unlink(BiomarkerOrganDataVars::BIO_RESOURCES);
		$this->unlink(BiomarkerOrganDataVars::BIO_PUBLICATIONS);
		$this->unlink(BiomarkerOrganDataVars::BIO_STUDYDATAS);
		//Delete this object's child objects

		//Delete object from the database
		$q = "DELETE FROM `BiomarkerOrganData` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "BiomarkerOrganData";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarker":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerOrganDataID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerOrganData (BiomarkerOrganDataID,BiomarkerID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarker\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerOrganData SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "Organ":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Organ WHERE BiomarkerOrganDataID=$this->objId AND OrganID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Organ (BiomarkerOrganDataID,OrganID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',OrganVar').") VALUES($this->objId,$remoteID,\"Organ\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Organ SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', OrganVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND OrganID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Resource WHERE BiomarkerOrganDataID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Resource (BiomarkerOrganDataID,ResourceID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Resource SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Publication WHERE BiomarkerOrganDataID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Publication (BiomarkerOrganDataID,PublicationID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Publication SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "StudyDatas":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganDataID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_BiomarkerOrganStudyData (BiomarkerOrganDataID,BiomarkerOrganStudyDataID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',BiomarkerOrganStudyDataVar').") VALUES($this->objId,$remoteID,\"StudyDatas\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_BiomarkerOrganStudyData SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganStudyDataVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
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
				$q = "DELETE FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerOrganDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerOrganDataVar = \"Biomarker\" ";
				break;
			case "Organ":
				$q = "DELETE FROM xr_BiomarkerOrganData_Organ WHERE BiomarkerOrganDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND OrganID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerOrganDataVar = \"Organ\" ";
				break;
			case "Resources":
				$q = "DELETE FROM xr_BiomarkerOrganData_Resource WHERE BiomarkerOrganDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND ResourceID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerOrganDataVar = \"Resources\" ";
				break;
			case "Publications":
				$q = "DELETE FROM xr_BiomarkerOrganData_Publication WHERE BiomarkerOrganDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PublicationID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerOrganDataVar = \"Publications\" ";
				break;
			case "StudyDatas":
				$q = "DELETE FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganDataID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerOrganDataVar = \"StudyDatas\" ";
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
			case "Organ":
				BiomarkerOrganDataXref::deleteByIDs($this->ID,"Organ",$objectID,"Organ");
				break;
			case "Biomarker":
				BiomarkerOrganDataXref::deleteByIDs($this->ID,"Biomarker",$objectID,"Biomarker");
				break;
			case "Resources":
				BiomarkerOrganDataXref::deleteByIDs($this->ID,"Resource",$objectID,"Resources");
				break;
			case "Publications":
				BiomarkerOrganDataXref::deleteByIDs($this->ID,"Publication",$objectID,"Publications");
				break;
			case "StudyDatas":
				BiomarkerOrganDataXref::deleteByIDs($this->ID,"BiomarkerOrganStudyData",$objectID,"StudyDatas");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voBiomarkerOrganData();
		$vo->objId = $this->objId;
		$vo->SensitivityMin = $this->SensitivityMin;
		$vo->SensitivityMax = $this->SensitivityMax;
		$vo->SensitivityComment = $this->SensitivityComment;
		$vo->SpecificityMin = $this->SpecificityMin;
		$vo->SpecificityMax = $this->SpecificityMax;
		$vo->SpecificityComment = $this->SpecificityComment;
		$vo->PPVMin = $this->PPVMin;
		$vo->PPVMax = $this->PPVMax;
		$vo->PPVComment = $this->PPVComment;
		$vo->NPVMin = $this->NPVMin;
		$vo->NPVMax = $this->NPVMax;
		$vo->NPVComment = $this->NPVComment;
		return $vo;
	}
	public function applyVO($voBiomarkerOrganData) {
		if(!empty($voBiomarkerOrganData->objId)){
			$this->objId = $voBiomarkerOrganData->objId;
		}
		if(!empty($voBiomarkerOrganData->SensitivityMin)){
			$this->SensitivityMin = $voBiomarkerOrganData->SensitivityMin;
		}
		if(!empty($voBiomarkerOrganData->SensitivityMax)){
			$this->SensitivityMax = $voBiomarkerOrganData->SensitivityMax;
		}
		if(!empty($voBiomarkerOrganData->SensitivityComment)){
			$this->SensitivityComment = $voBiomarkerOrganData->SensitivityComment;
		}
		if(!empty($voBiomarkerOrganData->SpecificityMin)){
			$this->SpecificityMin = $voBiomarkerOrganData->SpecificityMin;
		}
		if(!empty($voBiomarkerOrganData->SpecificityMax)){
			$this->SpecificityMax = $voBiomarkerOrganData->SpecificityMax;
		}
		if(!empty($voBiomarkerOrganData->SpecificityComment)){
			$this->SpecificityComment = $voBiomarkerOrganData->SpecificityComment;
		}
		if(!empty($voBiomarkerOrganData->PPVMin)){
			$this->PPVMin = $voBiomarkerOrganData->PPVMin;
		}
		if(!empty($voBiomarkerOrganData->PPVMax)){
			$this->PPVMax = $voBiomarkerOrganData->PPVMax;
		}
		if(!empty($voBiomarkerOrganData->PPVComment)){
			$this->PPVComment = $voBiomarkerOrganData->PPVComment;
		}
		if(!empty($voBiomarkerOrganData->NPVMin)){
			$this->NPVMin = $voBiomarkerOrganData->NPVMin;
		}
		if(!empty($voBiomarkerOrganData->NPVMax)){
			$this->NPVMax = $voBiomarkerOrganData->NPVMax;
		}
		if(!empty($voBiomarkerOrganData->NPVComment)){
			$this->NPVComment = $voBiomarkerOrganData->NPVComment;
		}
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:BiomarkerOrganData rdf:about=\"{$urlBase}/editors/showBiomarkerOrgan.php?b={$this->BiomarkerID}&amp;o={$this->OrganSite}\">\r\n<{$namespace}:objId>$this->objId</{$namespace}:objId>\r\n<{$namespace}:SensitivityMin>$this->SensitivityMin</{$namespace}:SensitivityMin>\r\n<{$namespace}:SensitivityMax>$this->SensitivityMax</{$namespace}:SensitivityMax>\r\n<{$namespace}:SensitivityComment>$this->SensitivityComment</{$namespace}:SensitivityComment>\r\n<{$namespace}:SpecificityMin>$this->SpecificityMin</{$namespace}:SpecificityMin>\r\n<{$namespace}:SpecificityMax>$this->SpecificityMax</{$namespace}:SpecificityMax>\r\n<{$namespace}:SpecificityComment>$this->SpecificityComment</{$namespace}:SpecificityComment>\r\n<{$namespace}:PPVMin>$this->PPVMin</{$namespace}:PPVMin>\r\n<{$namespace}:PPVMax>$this->PPVMax</{$namespace}:PPVMax>\r\n<{$namespace}:PPVComment>$this->PPVComment</{$namespace}:PPVComment>\r\n<{$namespace}:NPVMin>$this->NPVMin</{$namespace}:NPVMin>\r\n<{$namespace}:NPVMax>$this->NPVMax</{$namespace}:NPVMax>\r\n<{$namespace}:NPVComment>$this->NPVComment</{$namespace}:NPVComment>\r\n";
		if ($this->Organ != ''){$rdf .= $this->Organ->toRDFStub($namespace,$urlBase);}
		if ($this->Biomarker != ''){$rdf .= $this->Biomarker->toRDFStub($namespace,$urlBase);}
		foreach ($this->Resources as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->Publications as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->StudyDatas as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:BiomarkerOrganData>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:BiomarkerOrganData rdf:about=\"{$urlBase}/editors/showBiomarkerOrgan.php?b={$this->BiomarkerID}&amp;o={$this->OrganSite}\"/>\r\n";
		return $rdf;
	}
}

class voBiomarkerOrganData {
	public $objId;
	public $SensitivityMin;
	public $SensitivityMax;
	public $SensitivityComment;
	public $SpecificityMin;
	public $SpecificityMax;
	public $SpecificityComment;
	public $PPVMin;
	public $PPVMax;
	public $PPVComment;
	public $NPVMin;
	public $NPVMax;
	public $NPVComment;

	public function toAssocArray(){
		return array(
			"objId" => $this->objId,
			"SensitivityMin" => $this->SensitivityMin,
			"SensitivityMax" => $this->SensitivityMax,
			"SensitivityComment" => $this->SensitivityComment,
			"SpecificityMin" => $this->SpecificityMin,
			"SpecificityMax" => $this->SpecificityMax,
			"SpecificityComment" => $this->SpecificityComment,
			"PPVMin" => $this->PPVMin,
			"PPVMax" => $this->PPVMax,
			"PPVComment" => $this->PPVComment,
			"NPVMin" => $this->NPVMin,
			"NPVMax" => $this->NPVMax,
			"NPVComment" => $this->NPVComment,
		);
	}
	public function applyAssocArray($arr) {
		if(!empty($arr['objId'])){
			$this->objId = $arr['objId'];
		}
		if(!empty($arr['SensitivityMin'])){
			$this->SensitivityMin = $arr['SensitivityMin'];
		}
		if(!empty($arr['SensitivityMax'])){
			$this->SensitivityMax = $arr['SensitivityMax'];
		}
		if(!empty($arr['SensitivityComment'])){
			$this->SensitivityComment = $arr['SensitivityComment'];
		}
		if(!empty($arr['SpecificityMin'])){
			$this->SpecificityMin = $arr['SpecificityMin'];
		}
		if(!empty($arr['SpecificityMax'])){
			$this->SpecificityMax = $arr['SpecificityMax'];
		}
		if(!empty($arr['SpecificityComment'])){
			$this->SpecificityComment = $arr['SpecificityComment'];
		}
		if(!empty($arr['PPVMin'])){
			$this->PPVMin = $arr['PPVMin'];
		}
		if(!empty($arr['PPVMax'])){
			$this->PPVMax = $arr['PPVMax'];
		}
		if(!empty($arr['PPVComment'])){
			$this->PPVComment = $arr['PPVComment'];
		}
		if(!empty($arr['NPVMin'])){
			$this->NPVMin = $arr['NPVMin'];
		}
		if(!empty($arr['NPVMax'])){
			$this->NPVMax = $arr['NPVMax'];
		}
		if(!empty($arr['NPVComment'])){
			$this->NPVComment = $arr['NPVComment'];
		}
	}
}

class daoBiomarkerOrganData {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `BiomarkerOrganData` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchBiomarkerOrganDataException("No BiomarkerOrganData found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerOrganData();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `BiomarkerOrganData` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerOrganData();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `BiomarkerOrganData` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerOrganData();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `BiomarkerOrganData`"; 
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
		$q = "DELETE FROM `BiomarkerOrganData` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `BiomarkerOrganData` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "SensitivityMin=\"$vo->SensitivityMin\"" . ", ";
		$q .= "SensitivityMax=\"$vo->SensitivityMax\"" . ", ";
		$q .= "SensitivityComment=\"$vo->SensitivityComment\"" . ", ";
		$q .= "SpecificityMin=\"$vo->SpecificityMin\"" . ", ";
		$q .= "SpecificityMax=\"$vo->SpecificityMax\"" . ", ";
		$q .= "SpecificityComment=\"$vo->SpecificityComment\"" . ", ";
		$q .= "PPVMin=\"$vo->PPVMin\"" . ", ";
		$q .= "PPVMax=\"$vo->PPVMax\"" . ", ";
		$q .= "PPVComment=\"$vo->PPVComment\"" . ", ";
		$q .= "NPVMin=\"$vo->NPVMin\"" . ", ";
		$q .= "NPVMax=\"$vo->NPVMax\"" . ", ";
		$q .= "NPVComment=\"$vo->NPVComment\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `BiomarkerOrganData` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->SensitivityMin.'","'.$vo->SensitivityMax.'","'.$vo->SensitivityComment.'","'.$vo->SpecificityMin.'","'.$vo->SpecificityMax.'","'.$vo->SpecificityComment.'","'.$vo->PPVMin.'","'.$vo->PPVMax.'","'.$vo->PPVComment.'","'.$vo->NPVMin.'","'.$vo->NPVMax.'","'.$vo->NPVComment.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `BiomarkerOrganData`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->SensitivityMin = $result['SensitivityMin'];
		$vo->SensitivityMax = $result['SensitivityMax'];
		$vo->SensitivityComment = $result['SensitivityComment'];
		$vo->SpecificityMin = $result['SpecificityMin'];
		$vo->SpecificityMax = $result['SpecificityMax'];
		$vo->SpecificityComment = $result['SpecificityComment'];
		$vo->PPVMin = $result['PPVMin'];
		$vo->PPVMax = $result['PPVMax'];
		$vo->PPVComment = $result['PPVComment'];
		$vo->NPVMin = $result['NPVMin'];
		$vo->NPVMax = $result['NPVMax'];
		$vo->NPVComment = $result['NPVComment'];
	}

}

class NoSuchBiomarkerOrganDataException extends Exception {
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

class BiomarkerOrganDataXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "Biomarker":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerOrganDataID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerOrganData (BiomarkerOrganDataID,BiomarkerID,BiomarkerOrganDataVar) VALUES($localID,$remoteID,\"Biomarker\");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerOrganData SET BiomarkerOrganDataVar=\"{$variableName}\" WHERE BiomarkerOrganDataID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "Organ":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Organ WHERE BiomarkerOrganDataID=$localID AND OrganID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Organ (BiomarkerOrganDataID,OrganID,BiomarkerOrganDataVar) VALUES($localID,$remoteID,\"Organ\");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Organ SET BiomarkerOrganDataVar=\"{$variableName}\" WHERE BiomarkerOrganDataID=$localID AND OrganID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Resource WHERE BiomarkerOrganDataID=$localID AND ResourceID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Resource (BiomarkerOrganDataID,ResourceID,BiomarkerOrganDataVar) VALUES($localID,$remoteID,\"Resources\");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Resource SET BiomarkerOrganDataVar=\"{$variableName}\" WHERE BiomarkerOrganDataID=$localID AND ResourceID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Publication WHERE BiomarkerOrganDataID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Publication (BiomarkerOrganDataID,PublicationID,BiomarkerOrganDataVar) VALUES($localID,$remoteID,\"Publications\");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Publication SET BiomarkerOrganDataVar=\"{$variableName}\" WHERE BiomarkerOrganDataID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "StudyDatas":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganDataID=$localID AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_BiomarkerOrganStudyData (BiomarkerOrganDataID,BiomarkerOrganStudyDataID,BiomarkerOrganDataVar) VALUES($localID,$remoteID,\"StudyDatas\");";
				$q1 = "UPDATE xr_BiomarkerOrganData_BiomarkerOrganStudyData SET BiomarkerOrganDataVar=\"{$variableName}\" WHERE BiomarkerOrganDataID=$localID AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
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
				$q = "DELETE FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerOrganDataID = $localID AND BiomarkerID = $remoteID AND BiomarkerOrganDataVar = \"Biomarker\" LIMIT 1";
				break;
			case "Organ":
				$q = "DELETE FROM xr_BiomarkerOrganData_Organ WHERE BiomarkerOrganDataID = $localID AND OrganID = $remoteID AND BiomarkerOrganDataVar = \"Organ\" LIMIT 1";
				break;
			case "Resources":
				$q = "DELETE FROM xr_BiomarkerOrganData_Resource WHERE BiomarkerOrganDataID = $localID AND ResourceID = $remoteID AND BiomarkerOrganDataVar = \"Resources\" LIMIT 1";
				break;
			case "Publications":
				$q = "DELETE FROM xr_BiomarkerOrganData_Publication WHERE BiomarkerOrganDataID = $localID AND PublicationID = $remoteID AND BiomarkerOrganDataVar = \"Publications\" LIMIT 1";
				break;
			case "StudyDatas":
				$q = "DELETE FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganDataID = $localID AND BiomarkerOrganStudyDataID = $remoteID AND BiomarkerOrganDataVar = \"StudyDatas\" LIMIT 1";
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
				$q = "SELECT BiomarkerID AS ID FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerOrganDataID = {$local->ID} AND BiomarkerOrganDataVar = \"Biomarker\" ";
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
			case "Organ":
				$q = "SELECT OrganID AS ID FROM xr_BiomarkerOrganData_Organ WHERE BiomarkerOrganDataID = {$local->ID} AND BiomarkerOrganDataVar = \"Organ\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = Organ::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
					$rcount++;
				}
				break;
			case "Resources":
				$q = "SELECT ResourceID AS ID FROM xr_BiomarkerOrganData_Resource WHERE BiomarkerOrganDataID = {$local->ID} AND BiomarkerOrganDataVar = \"Resources\" ";
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
			case "Publications":
				$q = "SELECT PublicationID AS ID FROM xr_BiomarkerOrganData_Publication WHERE BiomarkerOrganDataID = {$local->ID} AND BiomarkerOrganDataVar = \"Publications\" ";
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
			case "StudyDatas":
				$q = "SELECT BiomarkerOrganStudyDataID AS ID FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganDataID = {$local->ID} AND BiomarkerOrganDataVar = \"StudyDatas\" ";
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
				$q = "DELETE FROM `xr_Biomarker_BiomarkerOrganData` WHERE BiomarkerOrganDataID = $objectID AND BiomarkerOrganDataVar = \"Biomarker\" ";
				break;
			case "Organ":
				$q = "DELETE FROM `xr_BiomarkerOrganData_Organ` WHERE BiomarkerOrganDataID = $objectID AND BiomarkerOrganDataVar = \"Organ\" ";
				break;
			case "Resources":
				$q = "DELETE FROM `xr_BiomarkerOrganData_Resource` WHERE BiomarkerOrganDataID = $objectID AND BiomarkerOrganDataVar = \"Resources\" ";
				break;
			case "Publications":
				$q = "DELETE FROM `xr_BiomarkerOrganData_Publication` WHERE BiomarkerOrganDataID = $objectID AND BiomarkerOrganDataVar = \"Publications\" ";
				break;
			case "StudyDatas":
				$q = "DELETE FROM `xr_BiomarkerOrganData_BiomarkerOrganStudyData` WHERE BiomarkerOrganDataID = $objectID AND BiomarkerOrganDataVar = \"StudyDatas\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class BiomarkerOrganDataActions {
	public static function associateOrgan($BiomarkerOrganDataID,$OrganID){
		BiomarkerOrganDataXref::purgeVariable($BiomarkerOrganDataID,"Organ");
		BiomarkerOrganDataXref::createByIDs($BiomarkerOrganDataID,"Organ",$OrganID,"Organ");
	}
	public static function dissociateOrgan($BiomarkerOrganDataID,$OrganID){
		BiomarkerOrganDataXref::deleteByIDs($BiomarkerOrganDataID,"Organ",$OrganID,"Organ");
	}
	public static function associateBiomarker($BiomarkerOrganDataID,$BiomarkerID){
		BiomarkerOrganDataXref::purgeVariable($BiomarkerOrganDataID,"Biomarker");
		BiomarkerOrganDataXref::createByIDs($BiomarkerOrganDataID,"Biomarker",$BiomarkerID,"Biomarker");
	}
	public static function dissociateBiomarker($BiomarkerOrganDataID,$BiomarkerID){
		BiomarkerOrganDataXref::deleteByIDs($BiomarkerOrganDataID,"Biomarker",$BiomarkerID,"Biomarker");
	}
	public static function associateResource($BiomarkerOrganDataID,$ResourceID){
		BiomarkerOrganDataXref::createByIDs($BiomarkerOrganDataID,"Resource",$ResourceID,"Resources");
	}
	public static function dissociateResource($BiomarkerOrganDataID,$ResourceID){
		BiomarkerOrganDataXref::deleteByIDs($BiomarkerOrganDataID,"Resource",$ResourceID,"Resources");
	}
	public static function associatePublication($BiomarkerOrganDataID,$PublicationID){
		BiomarkerOrganDataXref::createByIDs($BiomarkerOrganDataID,"Publication",$PublicationID,"Publications");
	}
	public static function dissociatePublication($BiomarkerOrganDataID,$PublicationID){
		BiomarkerOrganDataXref::deleteByIDs($BiomarkerOrganDataID,"Publication",$PublicationID,"Publications");
	}
	public static function associateStudyData($BiomarkerOrganDataID,$BiomarkerOrganStudyDataID){
		BiomarkerOrganDataXref::createByIDs($BiomarkerOrganDataID,"BiomarkerOrganStudyData",$BiomarkerOrganStudyDataID,"StudyDatas");
	}
	public static function dissociateStudyData($BiomarkerOrganDataID,$BiomarkerOrganStudyDataID){
		BiomarkerOrganDataXref::deleteByIDs($BiomarkerOrganDataID,"BiomarkerOrganStudyData",$BiomarkerOrganStudyDataID,"StudyDatas");
	}
}

?>