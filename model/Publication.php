<?php
class Publication {

	public static function Create($PubMedID) {
		$vo = new voPublication();
		$vo->PubMedID = $PubMedID;
		$dao = new daoPublication();
		$dao->insert(&$vo);
		$obj = new objPublication();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoPublication();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchPublicationException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objPublication();
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
						$o->Biomarkers = PublicationXref::retrieve($o,"Biomarker",$po,$lazyFetch,$limit,"Biomarkers");
						if ($o->Biomarkers == null){$o->Biomarkers = array();}
						$o->BiomarkerOrgans = PublicationXref::retrieve($o,"BiomarkerOrganData",$po,$lazyFetch,$limit,"BiomarkerOrgans");
						if ($o->BiomarkerOrgans == null){$o->BiomarkerOrgans = array();}
						$o->BiomarkerOrganStudies = PublicationXref::retrieve($o,"BiomarkerOrganStudyData",$po,$lazyFetch,$limit,"BiomarkerOrganStudies");
						if ($o->BiomarkerOrganStudies == null){$o->BiomarkerOrganStudies = array();}
						$o->BiomarkerStudies = PublicationXref::retrieve($o,"BiomarkerStudyData",$po,$lazyFetch,$limit,"BiomarkerStudies");
						if ($o->BiomarkerStudies == null){$o->BiomarkerStudies = array();}
						$o->Studies = PublicationXref::retrieve($o,"Study",$po,$lazyFetch,$limit,"Studies");
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
		$dao = new daoPublication();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchPublicationException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objPublication;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Biomarkers = array();
			$obj->BiomarkerOrgans = array();
			$obj->BiomarkerOrganStudies = array();
			$obj->BiomarkerStudies = array();
			$obj->Studies = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Biomarkers = PublicationXref::retrieve($obj,"Biomarker",array($obj),$lazyFetch,$limit,"Biomarkers");
			if ($obj->Biomarkers == null){$obj->Biomarkers = array();}
			$obj->BiomarkerOrgans = PublicationXref::retrieve($obj,"BiomarkerOrganData",array($obj),$lazyFetch,$limit,"BiomarkerOrgans");
			if ($obj->BiomarkerOrgans == null){$obj->BiomarkerOrgans = array();}
			$obj->BiomarkerOrganStudies = PublicationXref::retrieve($obj,"BiomarkerOrganStudyData",array($obj),$lazyFetch,$limit,"BiomarkerOrganStudies");
			if ($obj->BiomarkerOrganStudies == null){$obj->BiomarkerOrganStudies = array();}
			$obj->BiomarkerStudies = PublicationXref::retrieve($obj,"BiomarkerStudyData",array($obj),$lazyFetch,$limit,"BiomarkerStudies");
			if ($obj->BiomarkerStudies == null){$obj->BiomarkerStudies = array();}
			$obj->Studies = PublicationXref::retrieve($obj,"Study",array($obj),$lazyFetch,$limit,"Studies");
			if ($obj->Studies == null){$obj->Studies = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_Publication` WHERE PublicationID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Publication` WHERE PublicationID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Publication` WHERE PublicationID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Publication` WHERE PublicationID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_Study_Publication` WHERE PublicationID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `Publication` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_Publication` WHERE PublicationID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerStudyData_Publication` WHERE PublicationID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Publication` WHERE PublicationID = $objID");
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganStudyData_Publication` WHERE PublicationID = $objID");
		$db->safeQuery("DELETE FROM `xr_Study_Publication` WHERE PublicationID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `Publication` WHERE ID = $objID");
	}
	public static function Exists($PubMedID) {
		$dao = new daoPublication();
		try {
			$dao->getBy(array("PubMedID"),array($PubMedID));
		} catch (NoSuchPublicationException $e){
			return false;
		}
		return true;
	}
	public static function RetrieveUnique( $PubMedID,$lazyFetch=false) {
		$dao = new daoPublication();
		try {
			$results = $dao->getBy(array("PubMedID"),array($PubMedID));
			return Publication::RetrieveByID($results[0]->ID,$lazyFetch);
		} catch (NoSuchPublicationException $e){
			return false;
		}
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoPublication;
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

class PublicationVars {
	const PUB_OBJID = "objId";
	const PUB_PUBMEDID = "PubMedID";
	const PUB_TITLE = "Title";
	const PUB_AUTHOR = "Author";
	const PUB_JOURNAL = "Journal";
	const PUB_VOLUME = "Volume";
	const PUB_ISSUE = "Issue";
	const PUB_YEAR = "Year";
	const PUB_BIOMARKERS = "Biomarkers";
	const PUB_BIOMARKERORGANS = "BiomarkerOrgans";
	const PUB_BIOMARKERORGANSTUDIES = "BiomarkerOrganStudies";
	const PUB_BIOMARKERSTUDIES = "BiomarkerStudies";
	const PUB_STUDIES = "Studies";
}

class objPublication {

	const _TYPE = "Publication";
	private $XPress;
	public $objId = '';
	public $PubMedID = '';
	public $Title = '';
	public $Author = '';
	public $Journal = '';
	public $Volume = '';
	public $Issue = '';
	public $Year = '';
	public $Biomarkers = array();
	public $BiomarkerOrgans = array();
	public $BiomarkerOrganStudies = array();
	public $BiomarkerStudies = array();
	public $Studies = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Publication<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		$this->objId = $objId;
		$q = "SELECT * FROM `Publication` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->PubMedID = $result['PubMedID'];
			$this->Title = $result['Title'];
			$this->Author = $result['Author'];
			$this->Journal = $result['Journal'];
			$this->Volume = $result['Volume'];
			$this->Issue = $result['Issue'];
			$this->Year = $result['Year'];
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
		$this->PubMedID = '';
		$this->Title = '';
		$this->Author = '';
		$this->Journal = '';
		$this->Volume = '';
		$this->Issue = '';
		$this->Year = '';
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
	public function getPubMedID() {
		 return $this->PubMedID;
	}
	public function getTitle() {
		 return $this->Title;
	}
	public function getAuthor() {
		 return $this->Author;
	}
	public function getJournal() {
		 return $this->Journal;
	}
	public function getVolume() {
		 return $this->Volume;
	}
	public function getIssue() {
		 return $this->Issue;
	}
	public function getYear() {
		 return $this->Year;
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
	public function setPubMedID($value,$bSave = true) {
		$this->PubMedID = $value;
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
	public function setAuthor($value,$bSave = true) {
		$this->Author = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setJournal($value,$bSave = true) {
		$this->Journal = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setVolume($value,$bSave = true) {
		$this->Volume = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setIssue($value,$bSave = true) {
		$this->Issue = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setYear($value,$bSave = true) {
		$this->Year = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function create($PubMedID){
		$this->PubMedID = $PubMedID;
		$this->save();
	}
	public function inflate($parentObjects) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "Biomarkers":
		$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"Biomarkers\" ";
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
		$q = "SELECT BiomarkerStudyDataID AS objId FROM xr_BiomarkerStudyData_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"BiomarkerStudies\" ";
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
		$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"BiomarkerOrgans\" ";
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
		$q = "SELECT BiomarkerOrganStudyDataID AS objId FROM xr_BiomarkerOrganStudyData_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"BiomarkerOrganStudies\" ";
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
		$q = "SELECT StudyID AS objId FROM xr_Study_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"Studies\" ";
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
			$q = "INSERT INTO `Publication` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->PubMedID.'","'.$this->Title.'","'.$this->Author.'","'.$this->Journal.'","'.$this->Volume.'","'.$this->Issue.'","'.$this->Year.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Publication`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Publication` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`PubMedID`=\"$this->PubMedID\","; 
			$q .= "`Title`=\"$this->Title\","; 
			$q .= "`Author`=\"$this->Author\","; 
			$q .= "`Journal`=\"$this->Journal\","; 
			$q .= "`Volume`=\"$this->Volume\","; 
			$q .= "`Issue`=\"$this->Issue\","; 
			$q .= "`Year`=\"$this->Year\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(PublicationVars::PUB_BIOMARKERS);
		$this->unlink(PublicationVars::PUB_BIOMARKERORGANS);
		$this->unlink(PublicationVars::PUB_BIOMARKERORGANSTUDIES);
		$this->unlink(PublicationVars::PUB_BIOMARKERSTUDIES);
		$this->unlink(PublicationVars::PUB_STUDIES);
		//Delete object from the database
		$q = "DELETE FROM `Publication` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Publication";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarkers":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Publication WHERE PublicationID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_Publication (PublicationID,BiomarkerID,PublicationVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarkers\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerStudies":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Publication WHERE PublicationID=$this->objId AND BiomarkerStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Publication (PublicationID,BiomarkerStudyDataID,PublicationVar".(($remoteVar == '')? '' : ',BiomarkerStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerStudies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerStudyDataVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrgans":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Publication WHERE PublicationID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Publication (PublicationID,BiomarkerOrganDataID,PublicationVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrgans\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrganStudies":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Publication WHERE PublicationID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Publication (PublicationID,BiomarkerOrganStudyDataID,PublicationVar".(($remoteVar == '')? '' : ',BiomarkerOrganStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrganStudies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganStudyDataVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "Studies":
				$q  = "SELECT COUNT(*) FROM xr_Study_Publication WHERE PublicationID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_Publication (PublicationID,StudyID,PublicationVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Studies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
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
				$q = "DELETE FROM xr_Biomarker_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerID2 ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"Biomarkers\" ";
				break;
			case "BiomarkerStudies":
				$q = "DELETE FROM xr_BiomarkerStudyData_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerStudyDataID2 ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"BiomarkerStudies\" ";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM xr_BiomarkerOrganData_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID2 ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"BiomarkerOrgans\" ";
				break;
			case "BiomarkerOrganStudies":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganStudyDataID2 ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"BiomarkerOrganStudies\" ";
				break;
			case "Studies":
				$q = "DELETE FROM xr_Study_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND StudyID2 ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"Studies\" ";
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
	public function associate($objectID,$variableName) {
		switch ($variableName) {
			case "Biomarkers":
				PublicationXref::createByIDs($this->ID,"Biomarker",$objectID,"Biomarkers");
				break;
			case "BiomarkerOrgans":
				PublicationXref::createByIDs($this->ID,"BiomarkerOrganData",$objectID,"BiomarkerOrgans");
				break;
			case "BiomarkerOrganStudies":
				PublicationXref::createByIDs($this->ID,"BiomarkerOrganStudyData",$objectID,"BiomarkerOrganStudies");
				break;
			case "BiomarkerStudies":
				PublicationXref::createByIDs($this->ID,"BiomarkerStudyData",$objectID,"BiomarkerStudies");
				break;
			case "Studies":
				PublicationXref::createByIDs($this->ID,"Study",$objectID,"Studies");
				break;
			default: 
				return false;
		}
		return true;
	}
	public function dissociate($objectID,$variableName) {
		switch ($variableName) {
			case "Biomarkers":
				PublicationXref::deleteByIDs($this->ID,"Biomarker",$objectID,"Biomarkers");
				break;
			case "BiomarkerOrgans":
				PublicationXref::deleteByIDs($this->ID,"BiomarkerOrganData",$objectID,"BiomarkerOrgans");
				break;
			case "BiomarkerOrganStudies":
				PublicationXref::deleteByIDs($this->ID,"BiomarkerOrganStudyData",$objectID,"BiomarkerOrganStudies");
				break;
			case "BiomarkerStudies":
				PublicationXref::deleteByIDs($this->ID,"BiomarkerStudyData",$objectID,"BiomarkerStudies");
				break;
			case "Studies":
				PublicationXref::deleteByIDs($this->ID,"Study",$objectID,"Studies");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voPublication();
		$vo->objId = $this->objId;
		$vo->PubMedID = $this->PubMedID;
		$vo->Title = $this->Title;
		$vo->Author = $this->Author;
		$vo->Journal = $this->Journal;
		$vo->Volume = $this->Volume;
		$vo->Issue = $this->Issue;
		$vo->Year = $this->Year;
		return $vo;
	}
	public function applyVO($voPublication) {
		if(!empty($voPublication->objId)){
			$this->objId = $voPublication->objId;
		}
		if(!empty($voPublication->PubMedID)){
			$this->PubMedID = $voPublication->PubMedID;
		}
		if(!empty($voPublication->Title)){
			$this->Title = $voPublication->Title;
		}
		if(!empty($voPublication->Author)){
			$this->Author = $voPublication->Author;
		}
		if(!empty($voPublication->Journal)){
			$this->Journal = $voPublication->Journal;
		}
		if(!empty($voPublication->Volume)){
			$this->Volume = $voPublication->Volume;
		}
		if(!empty($voPublication->Issue)){
			$this->Issue = $voPublication->Issue;
		}
		if(!empty($voPublication->Year)){
			$this->Year = $voPublication->Year;
		}
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Publication rdf:about=\"{$urlBase}/editors/showPublication.php?p={$this->ID}\">\r\n<{$namespace}:objId>$this->objId</{$namespace}:objId>\r\n<{$namespace}:PubMedID>$this->PubMedID</{$namespace}:PubMedID>\r\n<{$namespace}:Title>$this->Title</{$namespace}:Title>\r\n<{$namespace}:Author>$this->Author</{$namespace}:Author>\r\n<{$namespace}:Journal>$this->Journal</{$namespace}:Journal>\r\n<{$namespace}:Volume>$this->Volume</{$namespace}:Volume>\r\n<{$namespace}:Issue>$this->Issue</{$namespace}:Issue>\r\n<{$namespace}:Year>$this->Year</{$namespace}:Year>\r\n";
		foreach ($this->Biomarkers as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->BiomarkerOrgans as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->BiomarkerOrganStudies as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->BiomarkerStudies as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->Studies as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:Publication>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Publication rdf:about=\"{$urlBase}/editors/showPublication.php?p={$this->ID}\"/>\r\n";
		return $rdf;
	}
}

class voPublication {
	public $objId;
	public $PubMedID;
	public $Title;
	public $Author;
	public $Journal;
	public $Volume;
	public $Issue;
	public $Year;

	public function toAssocArray(){
		return array(
			"objId" => $this->objId,
			"PubMedID" => $this->PubMedID,
			"Title" => $this->Title,
			"Author" => $this->Author,
			"Journal" => $this->Journal,
			"Volume" => $this->Volume,
			"Issue" => $this->Issue,
			"Year" => $this->Year,
		);
	}
	public function applyAssocArray($arr) {
		if(!empty($arr['objId'])){
			$this->objId = $arr['objId'];
		}
		if(!empty($arr['PubMedID'])){
			$this->PubMedID = $arr['PubMedID'];
		}
		if(!empty($arr['Title'])){
			$this->Title = $arr['Title'];
		}
		if(!empty($arr['Author'])){
			$this->Author = $arr['Author'];
		}
		if(!empty($arr['Journal'])){
			$this->Journal = $arr['Journal'];
		}
		if(!empty($arr['Volume'])){
			$this->Volume = $arr['Volume'];
		}
		if(!empty($arr['Issue'])){
			$this->Issue = $arr['Issue'];
		}
		if(!empty($arr['Year'])){
			$this->Year = $arr['Year'];
		}
	}
}

class daoPublication {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `Publication` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchPublicationException("No Publication found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voPublication();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Publication` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voPublication();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `Publication` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voPublication();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Publication`"; 
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
		$q = "DELETE FROM `Publication` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `Publication` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "PubMedID=\"$vo->PubMedID\"" . ", ";
		$q .= "Title=\"$vo->Title\"" . ", ";
		$q .= "Author=\"$vo->Author\"" . ", ";
		$q .= "Journal=\"$vo->Journal\"" . ", ";
		$q .= "Volume=\"$vo->Volume\"" . ", ";
		$q .= "Issue=\"$vo->Issue\"" . ", ";
		$q .= "Year=\"$vo->Year\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Publication` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->PubMedID.'","'.$vo->Title.'","'.$vo->Author.'","'.$vo->Journal.'","'.$vo->Volume.'","'.$vo->Issue.'","'.$vo->Year.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Publication`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->PubMedID = $result['PubMedID'];
		$vo->Title = $result['Title'];
		$vo->Author = $result['Author'];
		$vo->Journal = $result['Journal'];
		$vo->Volume = $result['Volume'];
		$vo->Issue = $result['Issue'];
		$vo->Year = $result['Year'];
	}

}

class NoSuchPublicationException extends Exception {
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

class PublicationXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "Biomarkers":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Publication WHERE PublicationID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_Publication (PublicationID,BiomarkerID,PublicationVar) VALUES($localID,$remoteID,\"Biomarkers\");";
				$q1 = "UPDATE xr_Biomarker_Publication SET PublicationVar=\"{$variableName}\" WHERE PublicationID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerStudies":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Publication WHERE PublicationID=$localID AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Publication (PublicationID,BiomarkerStudyDataID,PublicationVar) VALUES($localID,$remoteID,\"BiomarkerStudies\");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Publication SET PublicationVar=\"{$variableName}\" WHERE PublicationID=$localID AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrgans":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Publication WHERE PublicationID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Publication (PublicationID,BiomarkerOrganDataID,PublicationVar) VALUES($localID,$remoteID,\"BiomarkerOrgans\");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Publication SET PublicationVar=\"{$variableName}\" WHERE PublicationID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrganStudies":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Publication WHERE PublicationID=$localID AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Publication (PublicationID,BiomarkerOrganStudyDataID,PublicationVar) VALUES($localID,$remoteID,\"BiomarkerOrganStudies\");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Publication SET PublicationVar=\"{$variableName}\" WHERE PublicationID=$localID AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "Studies":
				$q  = "SELECT COUNT(*) FROM xr_Study_Publication WHERE PublicationID=$localID AND StudyID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Study_Publication (PublicationID,StudyID,PublicationVar) VALUES($localID,$remoteID,\"Studies\");";
				$q1 = "UPDATE xr_Study_Publication SET PublicationVar=\"{$variableName}\" WHERE PublicationID=$localID AND StudyID=$remoteID LIMIT 1 ";
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
				$q = "DELETE FROM xr_Biomarker_Publication WHERE PublicationID = $localID AND BiomarkerID = $remoteID AND PublicationVar = \"Biomarkers\" LIMIT 1";
				break;
			case "BiomarkerStudies":
				$q = "DELETE FROM xr_BiomarkerStudyData_Publication WHERE PublicationID = $localID AND BiomarkerStudyDataID = $remoteID AND PublicationVar = \"BiomarkerStudies\" LIMIT 1";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM xr_BiomarkerOrganData_Publication WHERE PublicationID = $localID AND BiomarkerOrganDataID = $remoteID AND PublicationVar = \"BiomarkerOrgans\" LIMIT 1";
				break;
			case "BiomarkerOrganStudies":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Publication WHERE PublicationID = $localID AND BiomarkerOrganStudyDataID = $remoteID AND PublicationVar = \"BiomarkerOrganStudies\" LIMIT 1";
				break;
			case "Studies":
				$q = "DELETE FROM xr_Study_Publication WHERE PublicationID = $localID AND StudyID = $remoteID AND PublicationVar = \"Studies\" LIMIT 1";
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
				$q = "SELECT BiomarkerID AS ID FROM xr_Biomarker_Publication WHERE PublicationID = {$local->ID} AND PublicationVar = \"Biomarkers\" ";
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
				$q = "SELECT BiomarkerStudyDataID AS ID FROM xr_BiomarkerStudyData_Publication WHERE PublicationID = {$local->ID} AND PublicationVar = \"BiomarkerStudies\" ";
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
				$q = "SELECT BiomarkerOrganDataID AS ID FROM xr_BiomarkerOrganData_Publication WHERE PublicationID = {$local->ID} AND PublicationVar = \"BiomarkerOrgans\" ";
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
				$q = "SELECT BiomarkerOrganStudyDataID AS ID FROM xr_BiomarkerOrganStudyData_Publication WHERE PublicationID = {$local->ID} AND PublicationVar = \"BiomarkerOrganStudies\" ";
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
				$q = "SELECT StudyID AS ID FROM xr_Study_Publication WHERE PublicationID = {$local->ID} AND PublicationVar = \"Studies\" ";
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
				$q = "DELETE FROM `xr_Biomarker_Publication` WHERE PublicationID = $objectID AND PublicationVar = \"Biomarkers\" ";
				break;
			case "BiomarkerStudies":
				$q = "DELETE FROM `xr_BiomarkerStudyData_Publication` WHERE PublicationID = $objectID AND PublicationVar = \"BiomarkerStudies\" ";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM `xr_BiomarkerOrganData_Publication` WHERE PublicationID = $objectID AND PublicationVar = \"BiomarkerOrgans\" ";
				break;
			case "BiomarkerOrganStudies":
				$q = "DELETE FROM `xr_BiomarkerOrganStudyData_Publication` WHERE PublicationID = $objectID AND PublicationVar = \"BiomarkerOrganStudies\" ";
				break;
			case "Studies":
				$q = "DELETE FROM `xr_Study_Publication` WHERE PublicationID = $objectID AND PublicationVar = \"Studies\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class PublicationActions {
	public static function associateBiomarker($PublicationID,$BiomarkerID){
		PublicationXref::createByIDs($PublicationID,"Biomarker",$BiomarkerID,"Biomarkers");
	}
	public static function dissociateBiomarker($PublicationID,$BiomarkerID){
		PublicationXref::deleteByIDs($PublicationID,"Biomarker",$BiomarkerID,"Biomarkers");
	}
	public static function associateBiomarkerOrgan($PublicationID,$BiomarkerOrganDataID){
		PublicationXref::createByIDs($PublicationID,"BiomarkerOrganData",$BiomarkerOrganDataID,"BiomarkerOrgans");
	}
	public static function dissociateBiomarkerOrgan($PublicationID,$BiomarkerOrganDataID){
		PublicationXref::deleteByIDs($PublicationID,"BiomarkerOrganData",$BiomarkerOrganDataID,"BiomarkerOrgans");
	}
	public static function associateBiomarkerOrganStudy($PublicationID,$BiomarkerOrganStudyDataID){
		PublicationXref::createByIDs($PublicationID,"BiomarkerOrganStudyData",$BiomarkerOrganStudyDataID,"BiomarkerOrganStudies");
	}
	public static function dissociateBiomarkerOrganStudy($PublicationID,$BiomarkerOrganStudyDataID){
		PublicationXref::deleteByIDs($PublicationID,"BiomarkerOrganStudyData",$BiomarkerOrganStudyDataID,"BiomarkerOrganStudies");
	}
	public static function associateBiomarkerStudy($PublicationID,$BiomarkerStudyDataID){
		PublicationXref::createByIDs($PublicationID,"BiomarkerStudyData",$BiomarkerStudyDataID,"BiomarkerStudies");
	}
	public static function dissociateBiomarkerStudy($PublicationID,$BiomarkerStudyDataID){
		PublicationXref::deleteByIDs($PublicationID,"BiomarkerStudyData",$BiomarkerStudyDataID,"BiomarkerStudies");
	}
	public static function associateStudy($PublicationID,$StudyID){
		PublicationXref::createByIDs($PublicationID,"Study",$StudyID,"Studies");
	}
	public static function dissociateStudy($PublicationID,$StudyID){
		PublicationXref::deleteByIDs($PublicationID,"Study",$StudyID,"Studies");
	}
}

?>