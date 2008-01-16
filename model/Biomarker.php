<?php
class Biomarker {

	public static function Create($Title) {
		$vo = new voBiomarker();
		$vo->Title = $Title;
		$dao = new daoBiomarker();
		$dao->insert(&$vo);
		$obj = new objBiomarker();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoBiomarker();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchBiomarkerException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objBiomarker();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->Aliases = array();
						$o->Studies = array();
						$o->OrganDatas = array();
						$o->Publications = array();
						$o->Resources = array();
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->Aliases = BiomarkerXref::retrieve($o,"BiomarkerAlias",$po,$lazyFetch,$limit,"Aliases");
						if ($o->Aliases == null){$o->Aliases = array();}
						$o->Studies = BiomarkerXref::retrieve($o,"BiomarkerStudyData",$po,$lazyFetch,$limit,"Studies");
						if ($o->Studies == null){$o->Studies = array();}
						$o->OrganDatas = BiomarkerXref::retrieve($o,"BiomarkerOrganData",$po,$lazyFetch,$limit,"OrganDatas");
						if ($o->OrganDatas == null){$o->OrganDatas = array();}
						$o->Publications = BiomarkerXref::retrieve($o,"Publication",$po,$lazyFetch,$limit,"Publications");
						if ($o->Publications == null){$o->Publications = array();}
						$o->Resources = BiomarkerXref::retrieve($o,"Resource",$po,$lazyFetch,$limit,"Resources");
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
		$dao = new daoBiomarker();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchBiomarkerException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objBiomarker;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Aliases = array();
			$obj->Studies = array();
			$obj->OrganDatas = array();
			$obj->Publications = array();
			$obj->Resources = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Aliases = BiomarkerXref::retrieve($obj,"BiomarkerAlias",array($obj),$lazyFetch,$limit,"Aliases");
			if ($obj->Aliases == null){$obj->Aliases = array();}
			$obj->Studies = BiomarkerXref::retrieve($obj,"BiomarkerStudyData",array($obj),$lazyFetch,$limit,"Studies");
			if ($obj->Studies == null){$obj->Studies = array();}
			$obj->OrganDatas = BiomarkerXref::retrieve($obj,"BiomarkerOrganData",array($obj),$lazyFetch,$limit,"OrganDatas");
			if ($obj->OrganDatas == null){$obj->OrganDatas = array();}
			$obj->Publications = BiomarkerXref::retrieve($obj,"Publication",array($obj),$lazyFetch,$limit,"Publications");
			if ($obj->Publications == null){$obj->Publications = array();}
			$obj->Resources = BiomarkerXref::retrieve($obj,"Resource",array($obj),$lazyFetch,$limit,"Resources");
			if ($obj->Resources == null){$obj->Resources = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		$rows = $db->safeGetAll("SELECT BiomarkerAliasID FROM `xr_Biomarker_BiomarkerAlias` WHERE BiomarkerID IN (".implode(",",$objectIDs).")");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerAliasID'];
		}
		if (sizeof($goners) > 0){BiomarkerAlias::MultiDelete($goners,$db);}
		$rows = $db->safeGetAll("SELECT BiomarkerStudyDataID FROM `xr_Biomarker_BiomarkerStudyData` WHERE BiomarkerID IN (".implode(",",$objectIDs).")");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerStudyDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerStudyData::MultiDelete($goners,$db);}
		$rows = $db->safeGetAll("SELECT BiomarkerOrganDataID FROM `xr_Biomarker_BiomarkerOrganData` WHERE BiomarkerID IN (".implode(",",$objectIDs).")");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerOrganDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerOrganData::MultiDelete($goners,$db);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerAlias` WHERE BiomarkerID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerStudyData` WHERE BiomarkerID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerOrganData` WHERE BiomarkerID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_Biomarker_Publication` WHERE BiomarkerID IN (".implode(",",$objectIDs).")");
		$db->safeQuery("DELETE FROM `xr_Biomarker_Resource` WHERE BiomarkerID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `Biomarker` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		$rows = $db->safeGetAll("SELECT BiomarkerAliasID FROM `xr_Biomarker_BiomarkerAlias` WHERE BiomarkerID = $objID ");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerAliasID'];
		}
		if (sizeof($goners) > 0){BiomarkerAlias::MultiDelete($goners,$db);}
		$rows = $db->safeGetAll("SELECT BiomarkerStudyDataID FROM `xr_Biomarker_BiomarkerStudyData` WHERE BiomarkerID = $objID ");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerStudyDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerStudyData::MultiDelete($goners,$db);}
		$rows = $db->safeGetAll("SELECT BiomarkerOrganDataID FROM `xr_Biomarker_BiomarkerOrganData` WHERE BiomarkerID = $objID ");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerOrganDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerOrganData::MultiDelete($goners,$db);}
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerAlias` WHERE BiomarkerID = $objID");
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerStudyData` WHERE BiomarkerID = $objID");
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerOrganData` WHERE BiomarkerID = $objID");
		$db->safeQuery("DELETE FROM `xr_Biomarker_Publication` WHERE BiomarkerID = $objID");
		$db->safeQuery("DELETE FROM `xr_Biomarker_Resource` WHERE BiomarkerID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `Biomarker` WHERE ID = $objID");
	}
	public static function Exists($Title) {
		$dao = new daoBiomarker();
		try {
			$dao->getBy(array("Title"),array($Title));
		} catch (NoSuchBiomarkerException $e){
			return false;
		}
		return true;
	}
	public static function RetrieveUnique( $Title,$lazyFetch=false) {
		$dao = new daoBiomarker();
		try {
			$results = $dao->getBy(array("Title"),array($Title));
			return Biomarker::RetrieveByID($results[0]->ID,$lazyFetch);
		} catch (NoSuchBiomarkerException $e){
			return false;
		}
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoBiomarker;
		$dao->save(&$vo);
	}
	public static function attach_Aliase($object,$BiomarkerAlias){
		$object->Aliases[] = $BiomarkerAlias;
	}
	public static function attach_Studie($object,$BiomarkerStudyData){
		$object->Studies[] = $BiomarkerStudyData;
	}
	public static function attach_OrganData($object,$BiomarkerOrganData){
		$object->OrganDatas[] = $BiomarkerOrganData;
	}
	public static function attach_Publication($object,$Publication){
		$object->Publications[] = $Publication;
	}
	public static function attach_Resource($object,$Resource){
		$object->Resources[] = $Resource;
	}
}

class BiomarkerVars {
	const BIO_OBJID = "objId";
	const BIO_EKE_ID = "EKE_ID";
	const BIO_BIOMARKERID = "BiomarkerID";
	const BIO_PANELID = "PanelID";
	const BIO_TITLE = "Title";
	const BIO_SHORTNAME = "ShortName";
	const BIO_DESCRIPTION = "Description";
	const BIO_QASTATE = "QAState";
	const BIO_PHASE = "Phase";
	const BIO_SECURITY = "Security";
	const BIO_TYPE = "Type";
	const BIO_ALIASES = "Aliases";
	const BIO_STUDIES = "Studies";
	const BIO_ORGANDATAS = "OrganDatas";
	const BIO_PUBLICATIONS = "Publications";
	const BIO_RESOURCES = "Resources";
}

class objBiomarker {

	const _TYPE = "Biomarker";
	private $XPress;
	public $objId = '';
	public $EKE_ID = '';
	public $BiomarkerID = '';
	public $PanelID = '';
	public $Title = '';
	public $ShortName = '';
	public $Description = '';
	public $QAState = '';
	public $Phase = '';
	public $Security = '';
	public $Type = '';
	public $Aliases = array();
	public $Studies = array();
	public $OrganDatas = array();
	public $Publications = array();
	public $Resources = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Biomarker<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		$this->objId = $objId;
		$q = "SELECT * FROM `Biomarker` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->EKE_ID = $result['EKE_ID'];
			$this->BiomarkerID = $result['BiomarkerID'];
			$this->PanelID = $result['PanelID'];
			$this->Title = $result['Title'];
			$this->ShortName = $result['ShortName'];
			$this->Description = $result['Description'];
			$this->QAState = $result['QAState'];
			$this->Phase = $result['Phase'];
			$this->Security = $result['Security'];
			$this->Type = $result['Type'];
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
				$q = "SELECT * FROM `Biomarker` WHERE `Title`=\"{$value}\" LIMIT 1";
				$r = $this->XPress->Database->safeQuery($q);
				if ($r->numRows() != 1){
					return false;
				} else {
					$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
					$this->objId = $result['objId'];
					$this->EKE_ID = $result['EKE_ID'];
					$this->BiomarkerID = $result['BiomarkerID'];
					$this->PanelID = $result['PanelID'];
					$this->Title = $result['Title'];
					$this->ShortName = $result['ShortName'];
					$this->Description = $result['Description'];
					$this->QAState = $result['QAState'];
					$this->Phase = $result['Phase'];
					$this->Security = $result['Security'];
					$this->Type = $result['Type'];
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
		$this->EKE_ID = '';
		$this->BiomarkerID = '';
		$this->PanelID = '';
		$this->Title = '';
		$this->ShortName = '';
		$this->Description = '';
		$this->QAState = '';
		$this->Phase = '';
		$this->Security = '';
		$this->Type = '';
		$this->Aliases = array();
		$this->Studies = array();
		$this->OrganDatas = array();
		$this->Publications = array();
		$this->Resources = array();
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getEKE_ID() {
		 return $this->EKE_ID;
	}
	public function getBiomarkerID() {
		 return $this->BiomarkerID;
	}
	public function getPanelID() {
		 return $this->PanelID;
	}
	public function getTitle() {
		 return $this->Title;
	}
	public function getShortName() {
		 return $this->ShortName;
	}
	public function getDescription() {
		 return $this->Description;
	}
	public function getQAState() {
		 return $this->QAState;
	}
	public function getPhase() {
		 return $this->Phase;
	}
	public function getSecurity() {
		 return $this->Security;
	}
	public function getType() {
		 return $this->Type;
	}
	public function getAliases() {
		 return $this->Aliases;
	}
	public function getStudies() {
		 return $this->Studies;
	}
	public function getOrganDatas() {
		 return $this->OrganDatas;
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
	public function setEKE_ID($value,$bSave = true) {
		$this->EKE_ID = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setBiomarkerID($value,$bSave = true) {
		$this->BiomarkerID = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setPanelID($value,$bSave = true) {
		$this->PanelID = $value;
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
	public function setShortName($value,$bSave = true) {
		$this->ShortName = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setDescription($value,$bSave = true) {
		$this->Description = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setQAState($value,$bSave = true) {
		$this->QAState = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setPhase($value,$bSave = true) {
		$this->Phase = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setSecurity($value,$bSave = true) {
		$this->Security = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setType($value,$bSave = true) {
		$this->Type = $value;
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
		// Inflate "Aliases":
		$q = "SELECT BiomarkerAliasID AS objId FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"Aliases\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerAlias($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Aliases[] = $obj;
			}
			$rcount++;
		}
		// Inflate "Studies":
		$q = "SELECT BiomarkerStudyDataID AS objId FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"Studies\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerStudyData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Studies[] = $obj;
			}
			$rcount++;
		}
		// Inflate "OrganDatas":
		$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"OrganDatas\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objBiomarkerOrganData($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->OrganDatas[] = $obj;
			}
			$rcount++;
		}
		// Inflate "Publications":
		$q = "SELECT PublicationID AS objId FROM xr_Biomarker_Publication WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"Publications\" ";
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
		$q = "SELECT ResourceID AS objId FROM xr_Biomarker_Resource WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"Resources\" ";
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
			$q = "INSERT INTO `Biomarker` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->EKE_ID.'","'.$this->BiomarkerID.'","'.$this->PanelID.'","'.$this->Title.'","'.$this->ShortName.'","'.$this->Description.'","'.$this->QAState.'","'.$this->Phase.'","'.$this->Security.'","'.$this->Type.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Biomarker`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Biomarker` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`EKE_ID`=\"$this->EKE_ID\","; 
			$q .= "`BiomarkerID`=\"$this->BiomarkerID\","; 
			$q .= "`PanelID`=\"$this->PanelID\","; 
			$q .= "`Title`=\"$this->Title\","; 
			$q .= "`ShortName`=\"$this->ShortName\","; 
			$q .= "`Description`=\"$this->Description\","; 
			$q .= "`QAState`=\"$this->QAState\","; 
			$q .= "`Phase`=\"$this->Phase\","; 
			$q .= "`Security`=\"$this->Security\","; 
			$q .= "`Type`=\"$this->Type\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerVars::BIO_ALIASES);
		$this->unlink(BiomarkerVars::BIO_STUDIES);
		$this->unlink(BiomarkerVars::BIO_ORGANDATAS);
		$this->unlink(BiomarkerVars::BIO_PUBLICATIONS);
		$this->unlink(BiomarkerVars::BIO_RESOURCES);
		//Delete this object's child objects
		foreach ($this->Aliases as $obj){
			$obj->delete();
		}
		foreach ($this->Studies as $obj){
			$obj->delete();
		}
		foreach ($this->OrganDatas as $obj){
			$obj->delete();
		}

		//Delete object from the database
		$q = "DELETE FROM `Biomarker` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Biomarker";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Aliases":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerID=$this->objId AND BiomarkerAliasID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerAlias (BiomarkerID,BiomarkerAliasID,BiomarkerVar".(($remoteVar == '')? '' : ',BiomarkerAliasVar').") VALUES($this->objId,$remoteID,\"Aliases\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerAlias SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerAliasVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND BiomarkerAliasID=$remoteID LIMIT 1 ";
				break;
			case "Studies":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerID=$this->objId AND BiomarkerStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerStudyData (BiomarkerID,BiomarkerStudyDataID,BiomarkerVar".(($remoteVar == '')? '' : ',BiomarkerStudyDataVar').") VALUES($this->objId,$remoteID,\"Studies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerStudyData SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerStudyDataVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "OrganDatas":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerOrganData (BiomarkerID,BiomarkerOrganDataID,BiomarkerVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"OrganDatas\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerOrganData SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Publication WHERE BiomarkerID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_Publication (BiomarkerID,PublicationID,BiomarkerVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_Publication SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Resource WHERE BiomarkerID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_Resource (BiomarkerID,ResourceID,BiomarkerVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_Resource SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
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
			case "Aliases":
				$q = "DELETE FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerAliasID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerVar = \"Aliases\" ";
				break;
			case "Studies":
				$q = "DELETE FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerVar = \"Studies\" ";
				break;
			case "OrganDatas":
				$q = "DELETE FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerVar = \"OrganDatas\" ";
				break;
			case "Publications":
				$q = "DELETE FROM xr_Biomarker_Publication WHERE BiomarkerID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PublicationID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerVar = \"Publications\" ";
				break;
			case "Resources":
				$q = "DELETE FROM xr_Biomarker_Resource WHERE BiomarkerID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND ResourceID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerVar = \"Resources\" ";
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
			case "Aliases":
				BiomarkerXref::deleteByIDs($this->ID,"BiomarkerAlias",$objectID,"Aliases");
				BiomarkerAlias::Delete($objectID);
				break;
			case "Studies":
				BiomarkerXref::deleteByIDs($this->ID,"BiomarkerStudyData",$objectID,"Studies");
				BiomarkerStudyData::Delete($objectID);
				break;
			case "OrganDatas":
				BiomarkerXref::deleteByIDs($this->ID,"BiomarkerOrganData",$objectID,"OrganDatas");
				BiomarkerOrganData::Delete($objectID);
				break;
			case "Publications":
				BiomarkerXref::deleteByIDs($this->ID,"Publication",$objectID,"Publications");
				break;
			case "Resources":
				BiomarkerXref::deleteByIDs($this->ID,"Resource",$objectID,"Resources");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voBiomarker();
		$vo->objId = $this->objId;
		$vo->EKE_ID = $this->EKE_ID;
		$vo->BiomarkerID = $this->BiomarkerID;
		$vo->PanelID = $this->PanelID;
		$vo->Title = $this->Title;
		$vo->ShortName = $this->ShortName;
		$vo->Description = $this->Description;
		$vo->QAState = $this->QAState;
		$vo->Phase = $this->Phase;
		$vo->Security = $this->Security;
		$vo->Type = $this->Type;
		return $vo;
	}
	public function applyVO($voBiomarker) {
		if(!empty($voBiomarker->objId)){
			$this->objId = $voBiomarker->objId;
		}
		if(!empty($voBiomarker->EKE_ID)){
			$this->EKE_ID = $voBiomarker->EKE_ID;
		}
		if(!empty($voBiomarker->BiomarkerID)){
			$this->BiomarkerID = $voBiomarker->BiomarkerID;
		}
		if(!empty($voBiomarker->PanelID)){
			$this->PanelID = $voBiomarker->PanelID;
		}
		if(!empty($voBiomarker->Title)){
			$this->Title = $voBiomarker->Title;
		}
		if(!empty($voBiomarker->ShortName)){
			$this->ShortName = $voBiomarker->ShortName;
		}
		if(!empty($voBiomarker->Description)){
			$this->Description = $voBiomarker->Description;
		}
		if(!empty($voBiomarker->QAState)){
			$this->QAState = $voBiomarker->QAState;
		}
		if(!empty($voBiomarker->Phase)){
			$this->Phase = $voBiomarker->Phase;
		}
		if(!empty($voBiomarker->Security)){
			$this->Security = $voBiomarker->Security;
		}
		if(!empty($voBiomarker->Type)){
			$this->Type = $voBiomarker->Type;
		}
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Biomarker rdf:about=\"{$urlBase}/editors/showBiomarker.php?m={$this->ID}\">\r\n<{$namespace}:objId>$this->objId</{$namespace}:objId>\r\n<{$namespace}:EKE_ID>$this->EKE_ID</{$namespace}:EKE_ID>\r\n<{$namespace}:BiomarkerID>$this->BiomarkerID</{$namespace}:BiomarkerID>\r\n<{$namespace}:PanelID>$this->PanelID</{$namespace}:PanelID>\r\n<{$namespace}:Title>$this->Title</{$namespace}:Title>\r\n<{$namespace}:ShortName>$this->ShortName</{$namespace}:ShortName>\r\n<{$namespace}:Description>$this->Description</{$namespace}:Description>\r\n<{$namespace}:QAState>$this->QAState</{$namespace}:QAState>\r\n<{$namespace}:Phase>$this->Phase</{$namespace}:Phase>\r\n<{$namespace}:Security>$this->Security</{$namespace}:Security>\r\n<{$namespace}:Type>$this->Type</{$namespace}:Type>\r\n";
		foreach ($this->Aliases as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->Studies as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->OrganDatas as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->Publications as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->Resources as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:Biomarker>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Biomarker rdf:about=\"{$urlBase}/editors/showBiomarker.php?m={$this->ID}\"/>\r\n";
		return $rdf;
	}
}

class voBiomarker {
	public $objId;
	public $EKE_ID;
	public $BiomarkerID;
	public $PanelID;
	public $Title;
	public $ShortName;
	public $Description;
	public $QAState;
	public $Phase;
	public $Security;
	public $Type;

	public function toAssocArray(){
		return array(
			"objId" => $this->objId,
			"EKE_ID" => $this->EKE_ID,
			"BiomarkerID" => $this->BiomarkerID,
			"PanelID" => $this->PanelID,
			"Title" => $this->Title,
			"ShortName" => $this->ShortName,
			"Description" => $this->Description,
			"QAState" => $this->QAState,
			"Phase" => $this->Phase,
			"Security" => $this->Security,
			"Type" => $this->Type,
		);
	}
	public function applyAssocArray($arr) {
		if(!empty($arr['objId'])){
			$this->objId = $arr['objId'];
		}
		if(!empty($arr['EKE_ID'])){
			$this->EKE_ID = $arr['EKE_ID'];
		}
		if(!empty($arr['BiomarkerID'])){
			$this->BiomarkerID = $arr['BiomarkerID'];
		}
		if(!empty($arr['PanelID'])){
			$this->PanelID = $arr['PanelID'];
		}
		if(!empty($arr['Title'])){
			$this->Title = $arr['Title'];
		}
		if(!empty($arr['ShortName'])){
			$this->ShortName = $arr['ShortName'];
		}
		if(!empty($arr['Description'])){
			$this->Description = $arr['Description'];
		}
		if(!empty($arr['QAState'])){
			$this->QAState = $arr['QAState'];
		}
		if(!empty($arr['Phase'])){
			$this->Phase = $arr['Phase'];
		}
		if(!empty($arr['Security'])){
			$this->Security = $arr['Security'];
		}
		if(!empty($arr['Type'])){
			$this->Type = $arr['Type'];
		}
	}
}

class daoBiomarker {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `Biomarker` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchBiomarkerException("No Biomarker found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Biomarker` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `Biomarker` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Biomarker`"; 
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
		$q = "DELETE FROM `Biomarker` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `Biomarker` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "EKE_ID=\"$vo->EKE_ID\"" . ", ";
		$q .= "BiomarkerID=\"$vo->BiomarkerID\"" . ", ";
		$q .= "PanelID=\"$vo->PanelID\"" . ", ";
		$q .= "Title=\"$vo->Title\"" . ", ";
		$q .= "ShortName=\"$vo->ShortName\"" . ", ";
		$q .= "Description=\"$vo->Description\"" . ", ";
		$q .= "QAState=\"$vo->QAState\"" . ", ";
		$q .= "Phase=\"$vo->Phase\"" . ", ";
		$q .= "Security=\"$vo->Security\"" . ", ";
		$q .= "Type=\"$vo->Type\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Biomarker` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->EKE_ID.'","'.$vo->BiomarkerID.'","'.$vo->PanelID.'","'.$vo->Title.'","'.$vo->ShortName.'","'.$vo->Description.'","'.$vo->QAState.'","'.$vo->Phase.'","'.$vo->Security.'","'.$vo->Type.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Biomarker`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->EKE_ID = $result['EKE_ID'];
		$vo->BiomarkerID = $result['BiomarkerID'];
		$vo->PanelID = $result['PanelID'];
		$vo->Title = $result['Title'];
		$vo->ShortName = $result['ShortName'];
		$vo->Description = $result['Description'];
		$vo->QAState = $result['QAState'];
		$vo->Phase = $result['Phase'];
		$vo->Security = $result['Security'];
		$vo->Type = $result['Type'];
	}

}

class NoSuchBiomarkerException extends Exception {
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

class BiomarkerXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "Aliases":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerID=$localID AND BiomarkerAliasID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerAlias (BiomarkerID,BiomarkerAliasID,BiomarkerVar) VALUES($localID,$remoteID,\"Aliases\");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerAlias SET BiomarkerVar=\"{$variableName}\" WHERE BiomarkerID=$localID AND BiomarkerAliasID=$remoteID LIMIT 1 ";
				break;
			case "Studies":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerID=$localID AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerStudyData (BiomarkerID,BiomarkerStudyDataID,BiomarkerVar) VALUES($localID,$remoteID,\"Studies\");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerStudyData SET BiomarkerVar=\"{$variableName}\" WHERE BiomarkerID=$localID AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "OrganDatas":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerOrganData (BiomarkerID,BiomarkerOrganDataID,BiomarkerVar) VALUES($localID,$remoteID,\"OrganDatas\");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerOrganData SET BiomarkerVar=\"{$variableName}\" WHERE BiomarkerID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Publication WHERE BiomarkerID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_Publication (BiomarkerID,PublicationID,BiomarkerVar) VALUES($localID,$remoteID,\"Publications\");";
				$q1 = "UPDATE xr_Biomarker_Publication SET BiomarkerVar=\"{$variableName}\" WHERE BiomarkerID=$localID AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Resource WHERE BiomarkerID=$localID AND ResourceID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_Resource (BiomarkerID,ResourceID,BiomarkerVar) VALUES($localID,$remoteID,\"Resources\");";
				$q1 = "UPDATE xr_Biomarker_Resource SET BiomarkerVar=\"{$variableName}\" WHERE BiomarkerID=$localID AND ResourceID=$remoteID LIMIT 1 ";
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
			case "Aliases":
				$q = "DELETE FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerID = $localID AND BiomarkerAliasID = $remoteID AND BiomarkerVar = \"Aliases\" LIMIT 1";
				break;
			case "Studies":
				$q = "DELETE FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerID = $localID AND BiomarkerStudyDataID = $remoteID AND BiomarkerVar = \"Studies\" LIMIT 1";
				break;
			case "OrganDatas":
				$q = "DELETE FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerID = $localID AND BiomarkerOrganDataID = $remoteID AND BiomarkerVar = \"OrganDatas\" LIMIT 1";
				break;
			case "Publications":
				$q = "DELETE FROM xr_Biomarker_Publication WHERE BiomarkerID = $localID AND PublicationID = $remoteID AND BiomarkerVar = \"Publications\" LIMIT 1";
				break;
			case "Resources":
				$q = "DELETE FROM xr_Biomarker_Resource WHERE BiomarkerID = $localID AND ResourceID = $remoteID AND BiomarkerVar = \"Resources\" LIMIT 1";
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
			case "Aliases":
				$q = "SELECT BiomarkerAliasID AS ID FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerID = {$local->ID} AND BiomarkerVar = \"Aliases\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = BiomarkerAlias::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
					$rcount++;
				}
				break;
			case "Studies":
				$q = "SELECT BiomarkerStudyDataID AS ID FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerID = {$local->ID} AND BiomarkerVar = \"Studies\" ";
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
			case "OrganDatas":
				$q = "SELECT BiomarkerOrganDataID AS ID FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerID = {$local->ID} AND BiomarkerVar = \"OrganDatas\" ";
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
				$q = "SELECT PublicationID AS ID FROM xr_Biomarker_Publication WHERE BiomarkerID = {$local->ID} AND BiomarkerVar = \"Publications\" ";
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
				$q = "SELECT ResourceID AS ID FROM xr_Biomarker_Resource WHERE BiomarkerID = {$local->ID} AND BiomarkerVar = \"Resources\" ";
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
			case "Aliases":
				$q = "DELETE FROM `xr_Biomarker_BiomarkerAlias` WHERE BiomarkerID = $objectID AND BiomarkerVar = \"Aliases\" ";
				break;
			case "Studies":
				$q = "DELETE FROM `xr_Biomarker_BiomarkerStudyData` WHERE BiomarkerID = $objectID AND BiomarkerVar = \"Studies\" ";
				break;
			case "OrganDatas":
				$q = "DELETE FROM `xr_Biomarker_BiomarkerOrganData` WHERE BiomarkerID = $objectID AND BiomarkerVar = \"OrganDatas\" ";
				break;
			case "Publications":
				$q = "DELETE FROM `xr_Biomarker_Publication` WHERE BiomarkerID = $objectID AND BiomarkerVar = \"Publications\" ";
				break;
			case "Resources":
				$q = "DELETE FROM `xr_Biomarker_Resource` WHERE BiomarkerID = $objectID AND BiomarkerVar = \"Resources\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class BiomarkerActions {
	public static function associateAlias($BiomarkerID,$BiomarkerAliasID){
		BiomarkerXref::createByIDs($BiomarkerID,"BiomarkerAlias",$BiomarkerAliasID,"Aliases");
	}
	public static function dissociateAlias($BiomarkerID,$BiomarkerAliasID){
		BiomarkerAlias::Delete($BiomarkerAliasID);
	}
	public static function associateStudy($BiomarkerID,$BiomarkerStudyDataID){
		BiomarkerXref::createByIDs($BiomarkerID,"BiomarkerStudyData",$BiomarkerStudyDataID,"Studies");
	}
	public static function dissociateStudy($BiomarkerID,$BiomarkerStudyDataID){
		BiomarkerStudyData::Delete($BiomarkerStudyDataID);
	}
	public static function associateOrganData($BiomarkerID,$BiomarkerOrganDataID){
		BiomarkerXref::createByIDs($BiomarkerID,"BiomarkerOrganData",$BiomarkerOrganDataID,"OrganDatas");
	}
	public static function dissociateOrganData($BiomarkerID,$BiomarkerOrganDataID){
		BiomarkerOrganData::Delete($BiomarkerOrganDataID);
	}
	public static function associatePublication($BiomarkerID,$PublicationID){
		BiomarkerXref::createByIDs($BiomarkerID,"Publication",$PublicationID,"Publications");
	}
	public static function dissociatePublication($BiomarkerID,$PublicationID){
		BiomarkerXref::deleteByIDs($BiomarkerID,"Publication",$PublicationID,"Publications");
	}
	public static function associateResource($BiomarkerID,$ResourceID){
		BiomarkerXref::createByIDs($BiomarkerID,"Resource",$ResourceID,"Resources");
	}
	public static function dissociateResource($BiomarkerID,$ResourceID){
		BiomarkerXref::deleteByIDs($BiomarkerID,"Resource",$ResourceID,"Resources");
	}
}

?>