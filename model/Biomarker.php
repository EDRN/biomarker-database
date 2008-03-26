<?php
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
	public $QAStateEnumValues = array("New","Under Review","Approved","Rejected");
	public $PhaseEnumValues = array("One (1)","Two (2)","Three (3)","Four (4)","Five (5)");
	public $SecurityEnumValues = array("Public","Private");
	public $TypeEnumValues = array("Epigenomics","Genomics","Proteomics","Glycomics","Nanotechnology","Metabalomics","Hypermethylation");
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
		if ($objId == 0) { return false; /* must not be zero */ }
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

?>