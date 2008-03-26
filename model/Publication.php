<?php
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
		if ($objId == 0) { return false; /* must not be zero */ }
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
	public function initializeByUniqueKey($key,$value,$inflate = true,$parentObjects = array()) {
		switch ($key) {
			case "PubMedID":
				$this->PubMedID = $value;
				$q = "SELECT * FROM `Publication` WHERE `PubMedID`=\"{$value}\" LIMIT 1";
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
				break;
			default:
				break;
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
	public function inflate($parentObjects = array()) {
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
		//Delete this object's child objects

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
				$q = "DELETE FROM xr_Biomarker_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"Biomarkers\" ";
				break;
			case "BiomarkerStudies":
				$q = "DELETE FROM xr_BiomarkerStudyData_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"BiomarkerStudies\" ";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM xr_BiomarkerOrganData_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"BiomarkerOrgans\" ";
				break;
			case "BiomarkerOrganStudies":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"BiomarkerOrganStudies\" ";
				break;
			case "Studies":
				$q = "DELETE FROM xr_Study_Publication WHERE PublicationID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND StudyID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PublicationVar = \"Studies\" ";
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

?>