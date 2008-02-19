<?php
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
	const BIO_QASTATE = "QAState";
	const BIO_PHASE = "Phase";
	const BIO_ORGAN = "Organ";
	const BIO_BIOMARKER = "Biomarker";
	const BIO_RESOURCES = "Resources";
	const BIO_PUBLICATIONS = "Publications";
	const BIO_STUDYDATAS = "StudyDatas";
}

class objBiomarkerOrganData {

	const _TYPE = "BiomarkerOrganData";
	private $XPress;
	public $QAStateEnumValues = array("New","Under Review","Approved","Rejected");
	public $PhaseEnumValues = array("One (1)","Two (2)","Three (3)","Four (4)","Five (5)");
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
	public $QAState = '';
	public $Phase = '';
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
		if ($objId == 0) { return false; /* must not be zero */ }
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
			$this->QAState = $result['QAState'];
			$this->Phase = $result['Phase'];
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
		$this->QAState = '';
		$this->Phase = '';
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
	public function getQAState() {
		 return $this->QAState;
	}
	public function getPhase() {
		 return $this->Phase;
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
			$q .= 'VALUES("'.$this->objId.'","'.$this->SensitivityMin.'","'.$this->SensitivityMax.'","'.$this->SensitivityComment.'","'.$this->SpecificityMin.'","'.$this->SpecificityMax.'","'.$this->SpecificityComment.'","'.$this->PPVMin.'","'.$this->PPVMax.'","'.$this->PPVComment.'","'.$this->NPVMin.'","'.$this->NPVMax.'","'.$this->NPVComment.'","'.$this->QAState.'","'.$this->Phase.'") ';
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
			$q .= "`NPVComment`=\"$this->NPVComment\","; 
			$q .= "`QAState`=\"$this->QAState\","; 
			$q .= "`Phase`=\"$this->Phase\" ";
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
		$vo->QAState = $this->QAState;
		$vo->Phase = $this->Phase;
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
		if(!empty($voBiomarkerOrganData->QAState)){
			$this->QAState = $voBiomarkerOrganData->QAState;
		}
		if(!empty($voBiomarkerOrganData->Phase)){
			$this->Phase = $voBiomarkerOrganData->Phase;
		}
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:BiomarkerOrganData rdf:about=\"{$urlBase}/editors/showBiomarkerOrgan.php?b={$this->BiomarkerID}&amp;o={$this->OrganSite}\">\r\n<{$namespace}:objId>$this->objId</{$namespace}:objId>\r\n<{$namespace}:SensitivityMin>$this->SensitivityMin</{$namespace}:SensitivityMin>\r\n<{$namespace}:SensitivityMax>$this->SensitivityMax</{$namespace}:SensitivityMax>\r\n<{$namespace}:SensitivityComment>$this->SensitivityComment</{$namespace}:SensitivityComment>\r\n<{$namespace}:SpecificityMin>$this->SpecificityMin</{$namespace}:SpecificityMin>\r\n<{$namespace}:SpecificityMax>$this->SpecificityMax</{$namespace}:SpecificityMax>\r\n<{$namespace}:SpecificityComment>$this->SpecificityComment</{$namespace}:SpecificityComment>\r\n<{$namespace}:PPVMin>$this->PPVMin</{$namespace}:PPVMin>\r\n<{$namespace}:PPVMax>$this->PPVMax</{$namespace}:PPVMax>\r\n<{$namespace}:PPVComment>$this->PPVComment</{$namespace}:PPVComment>\r\n<{$namespace}:NPVMin>$this->NPVMin</{$namespace}:NPVMin>\r\n<{$namespace}:NPVMax>$this->NPVMax</{$namespace}:NPVMax>\r\n<{$namespace}:NPVComment>$this->NPVComment</{$namespace}:NPVComment>\r\n<{$namespace}:QAState>$this->QAState</{$namespace}:QAState>\r\n<{$namespace}:Phase>$this->Phase</{$namespace}:Phase>\r\n";
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

?>