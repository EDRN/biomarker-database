<?php


class BiomarkerVars {
	const OBJID = "objId";
	const EKEID = "EKEID";
	const BIOMARKERID = "BiomarkerID";
	const ISPANEL = "IsPanel";
	const PANELID = "PanelID";
	const TITLE = "Title";
	const SHORTNAME = "ShortName";
	const DESCRIPTION = "Description";
	const QASTATE = "QAState";
	const PHASE = "Phase";
	const SECURITY = "Security";
	const TYPE = "Type";
	const ALIASES = "Aliases";
	const STUDIES = "Studies";
	const ORGANDATAS = "OrganDatas";
	const PUBLICATIONS = "Publications";
	const RESOURCES = "Resources";
	const PANELMEMBERS = "PanelMembers";
}

class BiomarkerFactory {
	public static function Create($Title){
		$o = new Biomarker();
		$o->Title = $Title;
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = BiomarkerVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Biomarker();
		switch ($key) {
			case BiomarkerVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Biomarker` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setEKEID($data['EKEID'],false);
				$o->setBiomarkerID($data['BiomarkerID'],false);
				$o->setIsPanel($data['IsPanel'],false);
				$o->setPanelID($data['PanelID'],false);
				$o->setTitle($data['Title'],false);
				$o->setShortName($data['ShortName'],false);
				$o->setDescription($data['Description'],false);
				$o->setQAState($data['QAState'],false);
				$o->setPhase($data['Phase'],false);
				$o->setSecurity($data['Security'],false);
				$o->setType($data['Type'],false);
				return $o;
				break;
			case BiomarkerVars::TITLE:
				$o->setTitle($value,false);
				$q = "SELECT * FROM `Biomarker` WHERE `Title`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) { return false;}
				if (! is_array($data)) {return false;}
				$o->setobjId($data['objId'],false);
				$o->setEKEID($data['EKEID'],false);
				$o->setBiomarkerID($data['BiomarkerID'],false);
				$o->setIsPanel($data['IsPanel'],false);
				$o->setPanelID($data['PanelID'],false);
				$o->setTitle($data['Title'],false);
				$o->setShortName($data['ShortName'],false);
				$o->setDescription($data['Description'],false);
				$o->setQAState($data['QAState'],false);
				$o->setPhase($data['Phase'],false);
				$o->setSecurity($data['Security'],false);
				$o->setType($data['Type'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Biomarker extends XPressObject {

	const _TYPE = "Biomarker";
	public $QAStateEnumValues = array("New","Under Review","Approved","Rejected");
	public $PhaseEnumValues = array("One (1)","Two (2)","Three (3)","Four (4)","Five (5)");
	public $SecurityEnumValues = array("Public","Private");
	public $TypeEnumValues = array("Gene","Protein","Genetic","Genomic","Epigenetic","Proteomic","Glycomic","Metabolomic");
	public $EKEID = '';
	public $BiomarkerID = '';
	public $IsPanel = '';
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
	public $PanelMembers = array();


	public function __construct($objId = 0) {
		//echo "creating object of type Biomarker<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getEKEID() {
		 return $this->EKEID;
	}
	public function getBiomarkerID() {
		 return $this->BiomarkerID;
	}
	public function getIsPanel() {
		 return $this->IsPanel;
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
		if ($this->Aliases != array()) {
			return $this->Aliases;
		} else {
			$this->inflate(BiomarkerVars::ALIASES);
			return $this->Aliases;
		}
	}
	public function getStudies() {
		if ($this->Studies != array()) {
			return $this->Studies;
		} else {
			$this->inflate(BiomarkerVars::STUDIES);
			return $this->Studies;
		}
	}
	public function getOrganDatas() {
		if ($this->OrganDatas != array()) {
			return $this->OrganDatas;
		} else {
			$this->inflate(BiomarkerVars::ORGANDATAS);
			return $this->OrganDatas;
		}
	}
	public function getPublications() {
		if ($this->Publications != array()) {
			return $this->Publications;
		} else {
			$this->inflate(BiomarkerVars::PUBLICATIONS);
			return $this->Publications;
		}
	}
	public function getResources() {
		if ($this->Resources != array()) {
			return $this->Resources;
		} else {
			$this->inflate(BiomarkerVars::RESOURCES);
			return $this->Resources;
		}
	}
	public function getPanelMembers() {
		if ($this->PanelMembers != array()) {
			return $this->PanelMembers;
		} else {
			$this->inflate(BiomarkerVars::PANELMEMBERS);
			return $this->PanelMembers;
		}
	}

	// Mutator Functions 
	public function setEKEID($value,$bSave = true) {
		$this->EKEID = $value;
		if ($bSave){
			$this->save(BiomarkerVars::EKEID);
		}
	}
	public function setBiomarkerID($value,$bSave = true) {
		$this->BiomarkerID = $value;
		if ($bSave){
			$this->save(BiomarkerVars::BIOMARKERID);
		}
	}
	public function setIsPanel($value,$bSave = true) {
		$this->IsPanel = $value;
		if ($bSave){
			$this->save(BiomarkerVars::ISPANEL);
		}
	}
	public function setPanelID($value,$bSave = true) {
		$this->PanelID = $value;
		if ($bSave){
			$this->save(BiomarkerVars::PANELID);
		}
	}
	public function setTitle($value,$bSave = true) {
		$this->Title = $value;
		if ($bSave){
			$this->save(BiomarkerVars::TITLE);
		}
	}
	public function setShortName($value,$bSave = true) {
		$this->ShortName = $value;
		if ($bSave){
			$this->save(BiomarkerVars::SHORTNAME);
		}
	}
	public function setDescription($value,$bSave = true) {
		$this->Description = $value;
		if ($bSave){
			$this->save(BiomarkerVars::DESCRIPTION);
		}
	}
	public function setQAState($value,$bSave = true) {
		$this->QAState = $value;
		if ($bSave){
			$this->save(BiomarkerVars::QASTATE);
		}
	}
	public function setPhase($value,$bSave = true) {
		$this->Phase = $value;
		if ($bSave){
			$this->save(BiomarkerVars::PHASE);
		}
	}
	public function setSecurity($value,$bSave = true) {
		$this->Security = $value;
		if ($bSave){
			$this->save(BiomarkerVars::SECURITY);
		}
	}
	public function setType($value,$bSave = true) {
		$this->Type = $value;
		if ($bSave){
			$this->save(BiomarkerVars::TYPE);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Aliases":
				// Inflate "Aliases":
				$q = "SELECT BiomarkerAliasID AS objId FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"Aliases\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Aliases = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Aliases[] = BiomarkerAliasFactory::retrieve($id[objId]);
				}
				break;
			case "Studies":
				// Inflate "Studies":
				$q = "SELECT BiomarkerStudyDataID AS objId FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"Studies\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Studies = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Studies[] = BiomarkerStudyDataFactory::retrieve($id[objId]);
				}
				break;
			case "OrganDatas":
				// Inflate "OrganDatas":
				$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"OrganDatas\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->OrganDatas = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->OrganDatas[] = BiomarkerOrganDataFactory::retrieve($id[objId]);
				}
				break;
			case "Publications":
				// Inflate "Publications":
				$q = "SELECT PublicationID AS objId FROM xr_Biomarker_Publication WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"Publications\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Publications = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Publications[] = PublicationFactory::retrieve($id[objId]);
				}
				break;
			case "Resources":
				// Inflate "Resources":
				$q = "SELECT ResourceID AS objId FROM xr_Biomarker_Resource WHERE BiomarkerID = {$this->objId} AND BiomarkerVar = \"Resources\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Resources = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Resources[] = ResourceFactory::retrieve($id[objId]);
				}
				break;
			case "PanelMembers":
				// Inflate "PanelMembers":
				$q = "SELECT BiomarkerID2 AS objId FROM xr_Biomarker_Biomarker WHERE BiomarkerID1 = {$this->objId} AND Var = \"PanelMembers\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->PanelMembers = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->PanelMembers[] = BiomarkerFactory::retrieve($id[objId]);
				}
				break;
			default:
				return false;
		}
		return true;
	}
	public function deflate(){
		// reset all member variables to initial settings
		$this->objId = '';
		$this->EKEID = '';
		$this->BiomarkerID = '';
		$this->IsPanel = '';
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
		$this->PanelMembers = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Biomarker` ";
			$q .= 'VALUES("","'.$this->EKEID.'","'.$this->BiomarkerID.'","'.$this->IsPanel.'","'.$this->PanelID.'","'.$this->Title.'","'.$this->ShortName.'","'.$this->Description.'","'.$this->QAState.'","'.$this->Phase.'","'.$this->Security.'","'.$this->Type.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Biomarker`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Biomarker` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Biomarker` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`EKEID`=\"{$this->EKEID}\","; 
				$q .= "`BiomarkerID`=\"{$this->BiomarkerID}\","; 
				$q .= "`IsPanel`=\"{$this->IsPanel}\","; 
				$q .= "`PanelID`=\"{$this->PanelID}\","; 
				$q .= "`Title`=\"{$this->Title}\","; 
				$q .= "`ShortName`=\"{$this->ShortName}\","; 
				$q .= "`Description`=\"{$this->Description}\","; 
				$q .= "`QAState`=\"{$this->QAState}\","; 
				$q .= "`Phase`=\"{$this->Phase}\","; 
				$q .= "`Security`=\"{$this->Security}\","; 
				$q .= "`Type`=\"{$this->Type}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects
		foreach ($this->getAliases() as $obj){
			$obj->delete();
		}
		foreach ($this->getStudies() as $obj){
			$obj->delete();
		}
		foreach ($this->getOrganDatas() as $obj){
			$obj->delete();
		}

		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerVars::ALIASES);
		$this->unlink(BiomarkerVars::STUDIES);
		$this->unlink(BiomarkerVars::ORGANDATAS);
		$this->unlink(BiomarkerVars::PUBLICATIONS);
		$this->unlink(BiomarkerVars::RESOURCES);
		$this->unlink(BiomarkerVars::PANELMEMBERS);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerAlias WHERE `BiomarkerID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerStudyData WHERE `BiomarkerID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerOrganData WHERE `BiomarkerID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_Publication WHERE `BiomarkerID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_Resource WHERE `BiomarkerID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_Biomarker WHERE (`BiomarkerID1`={$this->objId} OR `BiomarkerID2`={$this->objId})");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Biomarker WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Biomarker::_TYPE; //Biomarker
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Aliases":
				$test = "SELECT COUNT(*) FROM BiomarkerAlias WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerID=$this->objId AND BiomarkerAliasID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerAlias (BiomarkerID,BiomarkerAliasID,BiomarkerVar".(($remoteVar == '')? '' : ',BiomarkerAliasVar').") VALUES($this->objId,$remoteID,\"Aliases\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerAlias SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerAliasVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND BiomarkerAliasID=$remoteID LIMIT 1 ";
				break;
			case "Studies":
				$test = "SELECT COUNT(*) FROM BiomarkerStudyData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerID=$this->objId AND BiomarkerStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerStudyData (BiomarkerID,BiomarkerStudyDataID,BiomarkerVar".(($remoteVar == '')? '' : ',BiomarkerStudyDataVar').") VALUES($this->objId,$remoteID,\"Studies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerStudyData SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerStudyDataVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "OrganDatas":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerOrganData (BiomarkerID,BiomarkerOrganDataID,BiomarkerVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"OrganDatas\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerOrganData SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$test = "SELECT COUNT(*) FROM Publication WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Publication WHERE BiomarkerID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_Publication (BiomarkerID,PublicationID,BiomarkerVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_Publication SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$test = "SELECT COUNT(*) FROM Resource WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Resource WHERE BiomarkerID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_Resource (BiomarkerID,ResourceID,BiomarkerVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_Resource SET BiomarkerVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
				break;
			case "PanelMembers":
				$test = "SELECT COUNT(*) FROM Biomarker WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Biomarker WHERE BiomarkerID1=$this->objId AND BiomarkerID2=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_Biomarker (BiomarkerID1,BiomarkerID2,Var) VALUES($this->objId,$remoteID,\"PanelMembers\");";
				$q1 = "UPDATE xrBiomarker_Biomarker SET Var=\"{$variable}\" WHERE BiomarkerID1=$this->objId AND BiomarkerID2=$remoteID LIMIT 1 ";
				break;
			default:
				break;
		}
		if (1 != XPress::getInstance()->getDatabase()->getOne($test)) {
			return false; // The requested remote id does not exist!
		}
		$count  = XPress::getInstance()->getDatabase()->getOne($q);
		if ($count == 0){
			XPress::getInstance()->getDatabase()->query($q0);
		} else {
			XPress::getInstance()->getDatabase()->query($q1);
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
			case "PanelMembers":
				$q = "DELETE FROM xr_Biomarker_Biomarker WHERE BiomarkerID1 = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerID2 ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))). " AND Var = \"PanelMembers\" LIMIT 1";
				break;
			default:
				break;
		}
		$r  = XPress::getInstance()->getDatabase()->query($q);
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
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}

	// API Extensions 
	// -@-	// -@-
	// End API Extensions --
}

?>