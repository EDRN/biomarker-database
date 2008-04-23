<?php


/** Biomarker **/


class BiomarkerVars {
	const OBJID = "objId";
	const EKEID = "EKEID";
	const BIOMARKERID = "BiomarkerID";
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
	public $TypeEnumValues = array("Epigenomics","Genomics","Proteomics","Glycomics","Nanotechnology","Metabalomics","Hypermethylation");
	public $EKEID = '';
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
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Biomarker` ";
			$q .= 'VALUES("","'.$this->EKEID.'","'.$this->BiomarkerID.'","'.$this->PanelID.'","'.$this->Title.'","'.$this->ShortName.'","'.$this->Description.'","'.$this->QAState.'","'.$this->Phase.'","'.$this->Security.'","'.$this->Type.'") ';
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

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerAlias WHERE `BiomarkerID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerStudyData WHERE `BiomarkerID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerOrganData WHERE `BiomarkerID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_Publication WHERE `BiomarkerID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_Resource WHERE `BiomarkerID`={$this->objId}");

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

/** BiomarkerAlias **/


class BiomarkerAliasVars {
	const OBJID = "objId";
	const ALIAS = "Alias";
	const BIOMARKER = "Biomarker";
}

class BiomarkerAliasFactory {
	public static function Create($BiomarkerId){
		$o = new BiomarkerAlias();
		$o->save();
		$o->link(BiomarkerAliasVars::BIOMARKER,$BiomarkerId,BiomarkerVars::ALIASES);
		return $o;
	}
	public static function Retrieve($value,$key = BiomarkerAliasVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new BiomarkerAlias();
		switch ($key) {
			case BiomarkerAliasVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `BiomarkerAlias` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setAlias($data['Alias'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class BiomarkerAlias extends XPressObject {

	const _TYPE = "BiomarkerAlias";
	public $Alias = '';
	public $Biomarker = '';


	public function __construct($objId = 0) {
		//echo "creating object of type BiomarkerAlias<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getAlias() {
		 return $this->Alias;
	}
	public function getBiomarker() {
		if ($this->Biomarker != "") {
			return $this->Biomarker;
		} else {
			$this->inflate(BiomarkerAliasVars::BIOMARKER);
			return $this->Biomarker;
		}
	}

	// Mutator Functions 
	public function setAlias($value,$bSave = true) {
		$this->Alias = $value;
		if ($bSave){
			$this->save(BiomarkerAliasVars::ALIAS);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Biomarker":
				// Inflate "Biomarker":
				$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerAliasID = {$this->objId} AND BiomarkerAliasVar = \"Biomarker\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->Biomarker = BiomarkerFactory::retrieve($id[objId]);
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
		$this->Alias = '';
		$this->Biomarker = '';
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `BiomarkerAlias` ";
			$q .= 'VALUES("","'.$this->Alias.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `BiomarkerAlias`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `BiomarkerAlias` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `BiomarkerAlias` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`Alias`=\"{$this->Alias}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerAliasVars::BIOMARKER);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerAlias WHERE `BiomarkerAliasID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM BiomarkerAlias WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return BiomarkerAlias::_TYPE; //BiomarkerAlias
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarker":
				$test = "SELECT COUNT(*) FROM Biomarker WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerAliasID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerAlias (BiomarkerAliasID,BiomarkerID,BiomarkerAliasVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarker\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerAlias SET BiomarkerAliasVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE BiomarkerAliasID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
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
			case "Biomarker":
				$q = "DELETE FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerAliasID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerAliasVar = \"Biomarker\" ";
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

/** Study **/


class StudyVars {
	const OBJID = "objId";
	const EDRNID = "EDRNID";
	const FHCRCID = "FHCRCID";
	const DMCCID = "DMCCID";
	const ISEDRN = "IsEDRN";
	const TITLE = "Title";
	const STUDYABSTRACT = "StudyAbstract";
	const BIOMARKERPOPULATIONCHARACTERISTICS = "BiomarkerPopulationCharacteristics";
	const BPCDESCRIPTION = "BPCDescription";
	const DESIGN = "Design";
	const DESIGNDESCRIPTION = "DesignDescription";
	const BIOMARKERSTUDYTYPE = "BiomarkerStudyType";
	const BIOMARKERS = "Biomarkers";
	const BIOMARKERORGANS = "BiomarkerOrgans";
	const BIOMARKERORGANDATAS = "BiomarkerOrganDatas";
	const PUBLICATIONS = "Publications";
	const RESOURCES = "Resources";
}

class StudyFactory {
	public static function Create($Title){
		$o = new Study();
		$o->Title = $Title;
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = StudyVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Study();
		switch ($key) {
			case StudyVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Study` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setEDRNID($data['EDRNID'],false);
				$o->setFHCRCID($data['FHCRCID'],false);
				$o->setDMCCID($data['DMCCID'],false);
				$o->setIsEDRN($data['IsEDRN'],false);
				$o->setTitle($data['Title'],false);
				$o->setStudyAbstract($data['StudyAbstract'],false);
				$o->setBiomarkerPopulationCharacteristics($data['BiomarkerPopulationCharacteristics'],false);
				$o->setBPCDescription($data['BPCDescription'],false);
				$o->setDesign($data['Design'],false);
				$o->setDesignDescription($data['DesignDescription'],false);
				$o->setBiomarkerStudyType($data['BiomarkerStudyType'],false);
				return $o;
				break;
			case StudyVars::TITLE:
				$o->setTitle($value,false);
				$q = "SELECT * FROM `Study` WHERE `Title`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) { return false;}
				if (! is_array($data)) {return false;}
				$o->setobjId($data['objId'],false);
				$o->setEDRNID($data['EDRNID'],false);
				$o->setFHCRCID($data['FHCRCID'],false);
				$o->setDMCCID($data['DMCCID'],false);
				$o->setIsEDRN($data['IsEDRN'],false);
				$o->setTitle($data['Title'],false);
				$o->setStudyAbstract($data['StudyAbstract'],false);
				$o->setBiomarkerPopulationCharacteristics($data['BiomarkerPopulationCharacteristics'],false);
				$o->setBPCDescription($data['BPCDescription'],false);
				$o->setDesign($data['Design'],false);
				$o->setDesignDescription($data['DesignDescription'],false);
				$o->setBiomarkerStudyType($data['BiomarkerStudyType'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Study extends XPressObject {

	const _TYPE = "Study";
	public $BiomarkerPopulationCharacteristicsEnumValues = array("Case/Control","Longitudinal","Randomized");
	public $DesignEnumValues = array("Retrospective","Prospective Analysis","Cross Sectional");
	public $BiomarkerStudyTypeEnumValues = array("Registered","Unregistered");
	public $EDRNID = '';
	public $FHCRCID = '';
	public $DMCCID = '';
	public $IsEDRN = '';
	public $Title = '';
	public $StudyAbstract = '';
	public $BiomarkerPopulationCharacteristics = '';
	public $BPCDescription = '';
	public $Design = '';
	public $DesignDescription = '';
	public $BiomarkerStudyType = '';
	public $Biomarkers = array();
	public $BiomarkerOrgans = array();
	public $BiomarkerOrganDatas = array();
	public $Publications = array();
	public $Resources = array();


	public function __construct($objId = 0) {
		//echo "creating object of type Study<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getEDRNID() {
		 return $this->EDRNID;
	}
	public function getFHCRCID() {
		 return $this->FHCRCID;
	}
	public function getDMCCID() {
		 return $this->DMCCID;
	}
	public function getIsEDRN() {
		 return $this->IsEDRN;
	}
	public function getTitle() {
		 return $this->Title;
	}
	public function getStudyAbstract() {
		 return $this->StudyAbstract;
	}
	public function getBiomarkerPopulationCharacteristics() {
		 return $this->BiomarkerPopulationCharacteristics;
	}
	public function getBPCDescription() {
		 return $this->BPCDescription;
	}
	public function getDesign() {
		 return $this->Design;
	}
	public function getDesignDescription() {
		 return $this->DesignDescription;
	}
	public function getBiomarkerStudyType() {
		 return $this->BiomarkerStudyType;
	}
	public function getBiomarkers() {
		if ($this->Biomarkers != array()) {
			return $this->Biomarkers;
		} else {
			$this->inflate(StudyVars::BIOMARKERS);
			return $this->Biomarkers;
		}
	}
	public function getBiomarkerOrgans() {
		if ($this->BiomarkerOrgans != array()) {
			return $this->BiomarkerOrgans;
		} else {
			$this->inflate(StudyVars::BIOMARKERORGANS);
			return $this->BiomarkerOrgans;
		}
	}
	public function getBiomarkerOrganDatas() {
		if ($this->BiomarkerOrganDatas != array()) {
			return $this->BiomarkerOrganDatas;
		} else {
			$this->inflate(StudyVars::BIOMARKERORGANDATAS);
			return $this->BiomarkerOrganDatas;
		}
	}
	public function getPublications() {
		if ($this->Publications != array()) {
			return $this->Publications;
		} else {
			$this->inflate(StudyVars::PUBLICATIONS);
			return $this->Publications;
		}
	}
	public function getResources() {
		if ($this->Resources != array()) {
			return $this->Resources;
		} else {
			$this->inflate(StudyVars::RESOURCES);
			return $this->Resources;
		}
	}

	// Mutator Functions 
	public function setEDRNID($value,$bSave = true) {
		$this->EDRNID = $value;
		if ($bSave){
			$this->save(StudyVars::EDRNID);
		}
	}
	public function setFHCRCID($value,$bSave = true) {
		$this->FHCRCID = $value;
		if ($bSave){
			$this->save(StudyVars::FHCRCID);
		}
	}
	public function setDMCCID($value,$bSave = true) {
		$this->DMCCID = $value;
		if ($bSave){
			$this->save(StudyVars::DMCCID);
		}
	}
	public function setIsEDRN($value,$bSave = true) {
		$this->IsEDRN = $value;
		if ($bSave){
			$this->save(StudyVars::ISEDRN);
		}
	}
	public function setTitle($value,$bSave = true) {
		$this->Title = $value;
		if ($bSave){
			$this->save(StudyVars::TITLE);
		}
	}
	public function setStudyAbstract($value,$bSave = true) {
		$this->StudyAbstract = $value;
		if ($bSave){
			$this->save(StudyVars::STUDYABSTRACT);
		}
	}
	public function setBiomarkerPopulationCharacteristics($value,$bSave = true) {
		$this->BiomarkerPopulationCharacteristics = $value;
		if ($bSave){
			$this->save(StudyVars::BIOMARKERPOPULATIONCHARACTERISTICS);
		}
	}
	public function setBPCDescription($value,$bSave = true) {
		$this->BPCDescription = $value;
		if ($bSave){
			$this->save(StudyVars::BPCDESCRIPTION);
		}
	}
	public function setDesign($value,$bSave = true) {
		$this->Design = $value;
		if ($bSave){
			$this->save(StudyVars::DESIGN);
		}
	}
	public function setDesignDescription($value,$bSave = true) {
		$this->DesignDescription = $value;
		if ($bSave){
			$this->save(StudyVars::DESIGNDESCRIPTION);
		}
	}
	public function setBiomarkerStudyType($value,$bSave = true) {
		$this->BiomarkerStudyType = $value;
		if ($bSave){
			$this->save(StudyVars::BIOMARKERSTUDYTYPE);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Biomarkers":
				// Inflate "Biomarkers":
				$q = "SELECT BiomarkerStudyDataID AS objId FROM xr_BiomarkerStudyData_Study WHERE StudyID = {$this->objId} AND StudyVar = \"Biomarkers\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Biomarkers = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Biomarkers[] = BiomarkerStudyDataFactory::retrieve($id[objId]);
				}
				break;
			case "BiomarkerOrganDatas":
				// Inflate "BiomarkerOrganDatas":
				$q = "SELECT BiomarkerOrganStudyDataID AS objId FROM xr_BiomarkerOrganStudyData_Study WHERE StudyID = {$this->objId} AND StudyVar = \"BiomarkerOrganDatas\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->BiomarkerOrganDatas = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->BiomarkerOrganDatas[] = BiomarkerOrganStudyDataFactory::retrieve($id[objId]);
				}
				break;
			case "BiomarkerOrgans":
				// Inflate "BiomarkerOrgans":
				$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_Study_BiomarkerOrganData WHERE StudyID = {$this->objId} AND StudyVar = \"BiomarkerOrgans\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->BiomarkerOrgans = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->BiomarkerOrgans[] = BiomarkerOrganDataFactory::retrieve($id[objId]);
				}
				break;
			case "Publications":
				// Inflate "Publications":
				$q = "SELECT PublicationID AS objId FROM xr_Study_Publication WHERE StudyID = {$this->objId} AND StudyVar = \"Publications\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Publications = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Publications[] = PublicationFactory::retrieve($id[objId]);
				}
				break;
			case "Resources":
				// Inflate "Resources":
				$q = "SELECT ResourceID AS objId FROM xr_Study_Resource WHERE StudyID = {$this->objId} AND StudyVar = \"Resources\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Resources = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Resources[] = ResourceFactory::retrieve($id[objId]);
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
		$this->EDRNID = '';
		$this->FHCRCID = '';
		$this->DMCCID = '';
		$this->IsEDRN = '';
		$this->Title = '';
		$this->StudyAbstract = '';
		$this->BiomarkerPopulationCharacteristics = '';
		$this->BPCDescription = '';
		$this->Design = '';
		$this->DesignDescription = '';
		$this->BiomarkerStudyType = '';
		$this->Biomarkers = array();
		$this->BiomarkerOrgans = array();
		$this->BiomarkerOrganDatas = array();
		$this->Publications = array();
		$this->Resources = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Study` ";
			$q .= 'VALUES("","'.$this->EDRNID.'","'.$this->FHCRCID.'","'.$this->DMCCID.'","'.$this->IsEDRN.'","'.$this->Title.'","'.$this->StudyAbstract.'","'.$this->BiomarkerPopulationCharacteristics.'","'.$this->BPCDescription.'","'.$this->Design.'","'.$this->DesignDescription.'","'.$this->BiomarkerStudyType.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Study`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Study` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Study` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`EDRNID`=\"{$this->EDRNID}\","; 
				$q .= "`FHCRCID`=\"{$this->FHCRCID}\","; 
				$q .= "`DMCCID`=\"{$this->DMCCID}\","; 
				$q .= "`IsEDRN`=\"{$this->IsEDRN}\","; 
				$q .= "`Title`=\"{$this->Title}\","; 
				$q .= "`StudyAbstract`=\"{$this->StudyAbstract}\","; 
				$q .= "`BiomarkerPopulationCharacteristics`=\"{$this->BiomarkerPopulationCharacteristics}\","; 
				$q .= "`BPCDescription`=\"{$this->BPCDescription}\","; 
				$q .= "`Design`=\"{$this->Design}\","; 
				$q .= "`DesignDescription`=\"{$this->DesignDescription}\","; 
				$q .= "`BiomarkerStudyType`=\"{$this->BiomarkerStudyType}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects
		foreach ($this->getBiomarkers() as $obj){
			$obj->delete();
		}
		foreach ($this->getBiomarkerOrgans() as $obj){
			$obj->delete();
		}
		foreach ($this->getBiomarkerOrganDatas() as $obj){
			$obj->delete();
		}

		//Intelligently unlink this object from any other objects
		$this->unlink(StudyVars::BIOMARKERS);
		$this->unlink(StudyVars::BIOMARKERORGANS);
		$this->unlink(StudyVars::BIOMARKERORGANDATAS);
		$this->unlink(StudyVars::PUBLICATIONS);
		$this->unlink(StudyVars::RESOURCES);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerStudyData_Study WHERE `StudyID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganStudyData_Study WHERE `StudyID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Study_BiomarkerOrganData WHERE `StudyID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Study_Publication WHERE `StudyID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Study_Resource WHERE `StudyID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Study WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Study::_TYPE; //Study
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarkers":
				$test = "SELECT COUNT(*) FROM BiomarkerStudyData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Study WHERE StudyID=$this->objId AND BiomarkerStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Study (StudyID,BiomarkerStudyDataID,StudyVar".(($remoteVar == '')? '' : ',BiomarkerStudyDataVar').") VALUES($this->objId,$remoteID,\"Biomarkers\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Study SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerStudyDataVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrganDatas":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganStudyData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Study WHERE StudyID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Study (StudyID,BiomarkerOrganStudyDataID,StudyVar".(($remoteVar == '')? '' : ',BiomarkerOrganStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrganDatas\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Study SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganStudyDataVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrgans":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Study_BiomarkerOrganData WHERE StudyID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_BiomarkerOrganData (StudyID,BiomarkerOrganDataID,StudyVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrgans\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_BiomarkerOrganData SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$test = "SELECT COUNT(*) FROM Publication WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Study_Publication WHERE StudyID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_Publication (StudyID,PublicationID,StudyVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_Publication SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$test = "SELECT COUNT(*) FROM Resource WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Study_Resource WHERE StudyID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_Resource (StudyID,ResourceID,StudyVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_Resource SET StudyVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE StudyID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
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
			case "Biomarkers":
				$q = "DELETE FROM xr_BiomarkerStudyData_Study WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"Biomarkers\" ";
				break;
			case "BiomarkerOrganDatas":
				$q = "DELETE FROM xr_BiomarkerOrganStudyData_Study WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganStudyDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"BiomarkerOrganDatas\" ";
				break;
			case "BiomarkerOrgans":
				$q = "DELETE FROM xr_Study_BiomarkerOrganData WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"BiomarkerOrgans\" ";
				break;
			case "Publications":
				$q = "DELETE FROM xr_Study_Publication WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PublicationID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"Publications\" ";
				break;
			case "Resources":
				$q = "DELETE FROM xr_Study_Resource WHERE StudyID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND ResourceID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND StudyVar = \"Resources\" ";
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

/** BiomarkerStudyData **/


class BiomarkerStudyDataVars {
	const OBJID = "objId";
	const SENSITIVITY = "Sensitivity";
	const SPECIFICITY = "Specificity";
	const PPV = "PPV";
	const NPV = "NPV";
	const ASSAY = "Assay";
	const TECHNOLOGY = "Technology";
	const STUDY = "Study";
	const BIOMARKER = "Biomarker";
	const PUBLICATIONS = "Publications";
	const RESOURCES = "Resources";
}

class BiomarkerStudyDataFactory {
	public static function Create($StudyId,$BiomarkerId){
		$o = new BiomarkerStudyData();
		$o->save();
		$o->link(BiomarkerStudyDataVars::STUDY,$StudyId,StudyVars::BIOMARKERS);
		$o->link(BiomarkerStudyDataVars::BIOMARKER,$BiomarkerId,BiomarkerVars::STUDIES);
		return $o;
	}
	public static function Retrieve($value,$key = BiomarkerStudyDataVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new BiomarkerStudyData();
		switch ($key) {
			case BiomarkerStudyDataVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `BiomarkerStudyData` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setSensitivity($data['Sensitivity'],false);
				$o->setSpecificity($data['Specificity'],false);
				$o->setPPV($data['PPV'],false);
				$o->setNPV($data['NPV'],false);
				$o->setAssay($data['Assay'],false);
				$o->setTechnology($data['Technology'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class BiomarkerStudyData extends XPressObject {

	const _TYPE = "BiomarkerStudyData";
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


	public function __construct($objId = 0) {
		//echo "creating object of type BiomarkerStudyData<br/>";
		$this->objId = $objId;
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
		if ($this->Study != "") {
			return $this->Study;
		} else {
			$this->inflate(BiomarkerStudyDataVars::STUDY);
			return $this->Study;
		}
	}
	public function getBiomarker() {
		if ($this->Biomarker != "") {
			return $this->Biomarker;
		} else {
			$this->inflate(BiomarkerStudyDataVars::BIOMARKER);
			return $this->Biomarker;
		}
	}
	public function getPublications() {
		if ($this->Publications != array()) {
			return $this->Publications;
		} else {
			$this->inflate(BiomarkerStudyDataVars::PUBLICATIONS);
			return $this->Publications;
		}
	}
	public function getResources() {
		if ($this->Resources != array()) {
			return $this->Resources;
		} else {
			$this->inflate(BiomarkerStudyDataVars::RESOURCES);
			return $this->Resources;
		}
	}

	// Mutator Functions 
	public function setSensitivity($value,$bSave = true) {
		$this->Sensitivity = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::SENSITIVITY);
		}
	}
	public function setSpecificity($value,$bSave = true) {
		$this->Specificity = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::SPECIFICITY);
		}
	}
	public function setPPV($value,$bSave = true) {
		$this->PPV = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::PPV);
		}
	}
	public function setNPV($value,$bSave = true) {
		$this->NPV = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::NPV);
		}
	}
	public function setAssay($value,$bSave = true) {
		$this->Assay = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::ASSAY);
		}
	}
	public function setTechnology($value,$bSave = true) {
		$this->Technology = $value;
		if ($bSave){
			$this->save(BiomarkerStudyDataVars::TECHNOLOGY);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Biomarker":
				// Inflate "Biomarker":
				$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Biomarker\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->Biomarker = BiomarkerFactory::retrieve($id[objId]);
				}
				break;
			case "Study":
				// Inflate "Study":
				$q = "SELECT StudyID AS objId FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Study\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->Study = StudyFactory::retrieve($id[objId]);
				}
				break;
			case "Publications":
				// Inflate "Publications":
				$q = "SELECT PublicationID AS objId FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Publications\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Publications = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Publications[] = PublicationFactory::retrieve($id[objId]);
				}
				break;
			case "Resources":
				// Inflate "Resources":
				$q = "SELECT ResourceID AS objId FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID = {$this->objId} AND BiomarkerStudyDataVar = \"Resources\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Resources = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Resources[] = ResourceFactory::retrieve($id[objId]);
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
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `BiomarkerStudyData` ";
			$q .= 'VALUES("","'.$this->Sensitivity.'","'.$this->Specificity.'","'.$this->PPV.'","'.$this->NPV.'","'.$this->Assay.'","'.$this->Technology.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `BiomarkerStudyData`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `BiomarkerStudyData` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `BiomarkerStudyData` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`Sensitivity`=\"{$this->Sensitivity}\","; 
				$q .= "`Specificity`=\"{$this->Specificity}\","; 
				$q .= "`PPV`=\"{$this->PPV}\","; 
				$q .= "`NPV`=\"{$this->NPV}\","; 
				$q .= "`Assay`=\"{$this->Assay}\","; 
				$q .= "`Technology`=\"{$this->Technology}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerStudyDataVars::STUDY);
		$this->unlink(BiomarkerStudyDataVars::BIOMARKER);
		$this->unlink(BiomarkerStudyDataVars::PUBLICATIONS);
		$this->unlink(BiomarkerStudyDataVars::RESOURCES);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerStudyData WHERE `BiomarkerStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerStudyData_Study WHERE `BiomarkerStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerStudyData_Publication WHERE `BiomarkerStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerStudyData_Resource WHERE `BiomarkerStudyDataID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM BiomarkerStudyData WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return BiomarkerStudyData::_TYPE; //BiomarkerStudyData
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarker":
				$test = "SELECT COUNT(*) FROM Biomarker WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerStudyData WHERE BiomarkerStudyDataID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerStudyData (BiomarkerStudyDataID,BiomarkerID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarker\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerStudyData SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "Study":
				$test = "SELECT COUNT(*) FROM Study WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Study WHERE BiomarkerStudyDataID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Study (BiomarkerStudyDataID,StudyID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Study\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Study SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$test = "SELECT COUNT(*) FROM Publication WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Publication WHERE BiomarkerStudyDataID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Publication (BiomarkerStudyDataID,PublicationID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Publication SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$test = "SELECT COUNT(*) FROM Resource WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Resource WHERE BiomarkerStudyDataID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Resource (BiomarkerStudyDataID,ResourceID,BiomarkerStudyDataVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Resource SET BiomarkerStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerStudyDataID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
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

/** Organ **/


class OrganVars {
	const OBJID = "objId";
	const NAME = "Name";
	const ORGANDATAS = "OrganDatas";
}

class OrganFactory {
	public static function Create($Name){
		$o = new Organ();
		$o->Name = $Name;
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = OrganVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Organ();
		switch ($key) {
			case OrganVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Organ` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setName($data['Name'],false);
				return $o;
				break;
			case OrganVars::NAME:
				$o->setName($value,false);
				$q = "SELECT * FROM `Organ` WHERE `Name`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) { return false;}
				if (! is_array($data)) {return false;}
				$o->setobjId($data['objId'],false);
				$o->setName($data['Name'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Organ extends XPressObject {

	const _TYPE = "Organ";
	public $Name = '';
	public $OrganDatas = array();


	public function __construct($objId = 0) {
		//echo "creating object of type Organ<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getName() {
		 return $this->Name;
	}
	public function getOrganDatas() {
		if ($this->OrganDatas != array()) {
			return $this->OrganDatas;
		} else {
			$this->inflate(OrganVars::ORGANDATAS);
			return $this->OrganDatas;
		}
	}

	// Mutator Functions 
	public function setName($value,$bSave = true) {
		$this->Name = $value;
		if ($bSave){
			$this->save(OrganVars::NAME);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "OrganDatas":
				// Inflate "OrganDatas":
				$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_Organ WHERE OrganID = {$this->objId} AND OrganVar = \"OrganDatas\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->OrganDatas = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->OrganDatas[] = BiomarkerOrganDataFactory::retrieve($id[objId]);
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
		$this->Name = '';
		$this->OrganDatas = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Organ` ";
			$q .= 'VALUES("","'.$this->Name.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Organ`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Organ` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Organ` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`Name`=\"{$this->Name}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects
		foreach ($this->getOrganDatas() as $obj){
			$obj->delete();
		}

		//Intelligently unlink this object from any other objects
		$this->unlink(OrganVars::ORGANDATAS);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganData_Organ WHERE `OrganID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Organ WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Organ::_TYPE; //Organ
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "OrganDatas":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Organ WHERE OrganID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Organ (OrganID,BiomarkerOrganDataID,OrganVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"OrganDatas\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Organ SET OrganVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE OrganID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
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
			case "OrganDatas":
				$q = "DELETE FROM xr_BiomarkerOrganData_Organ WHERE OrganID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND OrganVar = \"OrganDatas\" ";
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

/** BiomarkerOrganData **/


class BiomarkerOrganDataVars {
	const OBJID = "objId";
	const SENSITIVITYMIN = "SensitivityMin";
	const SENSITIVITYMAX = "SensitivityMax";
	const SENSITIVITYCOMMENT = "SensitivityComment";
	const SPECIFICITYMIN = "SpecificityMin";
	const SPECIFICITYMAX = "SpecificityMax";
	const SPECIFICITYCOMMENT = "SpecificityComment";
	const PPVMIN = "PPVMin";
	const PPVMAX = "PPVMax";
	const PPVCOMMENT = "PPVComment";
	const NPVMIN = "NPVMin";
	const NPVMAX = "NPVMax";
	const NPVCOMMENT = "NPVComment";
	const QASTATE = "QAState";
	const PHASE = "Phase";
	const DESCRIPTION = "Description";
	const ORGAN = "Organ";
	const BIOMARKER = "Biomarker";
	const RESOURCES = "Resources";
	const PUBLICATIONS = "Publications";
	const STUDYDATAS = "StudyDatas";
}

class BiomarkerOrganDataFactory {
	public static function Create($OrganId,$BiomarkerId){
		$o = new BiomarkerOrganData();
		$o->save();
		$o->link(BiomarkerOrganDataVars::ORGAN,$OrganId,OrganVars::ORGANDATAS);
		$o->link(BiomarkerOrganDataVars::BIOMARKER,$BiomarkerId,BiomarkerVars::ORGANDATAS);
		return $o;
	}
	public static function Retrieve($value,$key = BiomarkerOrganDataVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new BiomarkerOrganData();
		switch ($key) {
			case BiomarkerOrganDataVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `BiomarkerOrganData` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setSensitivityMin($data['SensitivityMin'],false);
				$o->setSensitivityMax($data['SensitivityMax'],false);
				$o->setSensitivityComment($data['SensitivityComment'],false);
				$o->setSpecificityMin($data['SpecificityMin'],false);
				$o->setSpecificityMax($data['SpecificityMax'],false);
				$o->setSpecificityComment($data['SpecificityComment'],false);
				$o->setPPVMin($data['PPVMin'],false);
				$o->setPPVMax($data['PPVMax'],false);
				$o->setPPVComment($data['PPVComment'],false);
				$o->setNPVMin($data['NPVMin'],false);
				$o->setNPVMax($data['NPVMax'],false);
				$o->setNPVComment($data['NPVComment'],false);
				$o->setQAState($data['QAState'],false);
				$o->setPhase($data['Phase'],false);
				$o->setDescription($data['Description'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class BiomarkerOrganData extends XPressObject {

	const _TYPE = "BiomarkerOrganData";
	public $QAStateEnumValues = array("New","Under Review","Approved","Rejected");
	public $PhaseEnumValues = array("One (1)","Two (2)","Three (3)","Four (4)","Five (5)");
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
	public $Description = '';
	public $Organ = '';
	public $Biomarker = '';
	public $Resources = array();
	public $Publications = array();
	public $StudyDatas = array();


	public function __construct($objId = 0) {
		//echo "creating object of type BiomarkerOrganData<br/>";
		$this->objId = $objId;
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
	public function getDescription() {
		 return $this->Description;
	}
	public function getOrgan() {
		if ($this->Organ != "") {
			return $this->Organ;
		} else {
			$this->inflate(BiomarkerOrganDataVars::ORGAN);
			return $this->Organ;
		}
	}
	public function getBiomarker() {
		if ($this->Biomarker != "") {
			return $this->Biomarker;
		} else {
			$this->inflate(BiomarkerOrganDataVars::BIOMARKER);
			return $this->Biomarker;
		}
	}
	public function getResources() {
		if ($this->Resources != array()) {
			return $this->Resources;
		} else {
			$this->inflate(BiomarkerOrganDataVars::RESOURCES);
			return $this->Resources;
		}
	}
	public function getPublications() {
		if ($this->Publications != array()) {
			return $this->Publications;
		} else {
			$this->inflate(BiomarkerOrganDataVars::PUBLICATIONS);
			return $this->Publications;
		}
	}
	public function getStudyDatas() {
		if ($this->StudyDatas != array()) {
			return $this->StudyDatas;
		} else {
			$this->inflate(BiomarkerOrganDataVars::STUDYDATAS);
			return $this->StudyDatas;
		}
	}

	// Mutator Functions 
	public function setSensitivityMin($value,$bSave = true) {
		$this->SensitivityMin = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::SENSITIVITYMIN);
		}
	}
	public function setSensitivityMax($value,$bSave = true) {
		$this->SensitivityMax = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::SENSITIVITYMAX);
		}
	}
	public function setSensitivityComment($value,$bSave = true) {
		$this->SensitivityComment = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::SENSITIVITYCOMMENT);
		}
	}
	public function setSpecificityMin($value,$bSave = true) {
		$this->SpecificityMin = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::SPECIFICITYMIN);
		}
	}
	public function setSpecificityMax($value,$bSave = true) {
		$this->SpecificityMax = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::SPECIFICITYMAX);
		}
	}
	public function setSpecificityComment($value,$bSave = true) {
		$this->SpecificityComment = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::SPECIFICITYCOMMENT);
		}
	}
	public function setPPVMin($value,$bSave = true) {
		$this->PPVMin = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::PPVMIN);
		}
	}
	public function setPPVMax($value,$bSave = true) {
		$this->PPVMax = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::PPVMAX);
		}
	}
	public function setPPVComment($value,$bSave = true) {
		$this->PPVComment = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::PPVCOMMENT);
		}
	}
	public function setNPVMin($value,$bSave = true) {
		$this->NPVMin = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::NPVMIN);
		}
	}
	public function setNPVMax($value,$bSave = true) {
		$this->NPVMax = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::NPVMAX);
		}
	}
	public function setNPVComment($value,$bSave = true) {
		$this->NPVComment = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::NPVCOMMENT);
		}
	}
	public function setQAState($value,$bSave = true) {
		$this->QAState = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::QASTATE);
		}
	}
	public function setPhase($value,$bSave = true) {
		$this->Phase = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::PHASE);
		}
	}
	public function setDescription($value,$bSave = true) {
		$this->Description = $value;
		if ($bSave){
			$this->save(BiomarkerOrganDataVars::DESCRIPTION);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Biomarker":
				// Inflate "Biomarker":
				$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"Biomarker\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->Biomarker = BiomarkerFactory::retrieve($id[objId]);
				}
				break;
			case "Organ":
				// Inflate "Organ":
				$q = "SELECT OrganID AS objId FROM xr_BiomarkerOrganData_Organ WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"Organ\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->Organ = OrganFactory::retrieve($id[objId]);
				}
				break;
			case "Resources":
				// Inflate "Resources":
				$q = "SELECT ResourceID AS objId FROM xr_BiomarkerOrganData_Resource WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"Resources\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Resources = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Resources[] = ResourceFactory::retrieve($id[objId]);
				}
				break;
			case "Publications":
				// Inflate "Publications":
				$q = "SELECT PublicationID AS objId FROM xr_BiomarkerOrganData_Publication WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"Publications\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Publications = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Publications[] = PublicationFactory::retrieve($id[objId]);
				}
				break;
			case "StudyDatas":
				// Inflate "StudyDatas":
				$q = "SELECT BiomarkerOrganStudyDataID AS objId FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganDataID = {$this->objId} AND BiomarkerOrganDataVar = \"StudyDatas\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->StudyDatas = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->StudyDatas[] = BiomarkerOrganStudyDataFactory::retrieve($id[objId]);
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
		$this->Description = '';
		$this->Organ = '';
		$this->Biomarker = '';
		$this->Resources = array();
		$this->Publications = array();
		$this->StudyDatas = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `BiomarkerOrganData` ";
			$q .= 'VALUES("","'.$this->SensitivityMin.'","'.$this->SensitivityMax.'","'.$this->SensitivityComment.'","'.$this->SpecificityMin.'","'.$this->SpecificityMax.'","'.$this->SpecificityComment.'","'.$this->PPVMin.'","'.$this->PPVMax.'","'.$this->PPVComment.'","'.$this->NPVMin.'","'.$this->NPVMax.'","'.$this->NPVComment.'","'.$this->QAState.'","'.$this->Phase.'","'.$this->Description.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `BiomarkerOrganData`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `BiomarkerOrganData` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `BiomarkerOrganData` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`SensitivityMin`=\"{$this->SensitivityMin}\","; 
				$q .= "`SensitivityMax`=\"{$this->SensitivityMax}\","; 
				$q .= "`SensitivityComment`=\"{$this->SensitivityComment}\","; 
				$q .= "`SpecificityMin`=\"{$this->SpecificityMin}\","; 
				$q .= "`SpecificityMax`=\"{$this->SpecificityMax}\","; 
				$q .= "`SpecificityComment`=\"{$this->SpecificityComment}\","; 
				$q .= "`PPVMin`=\"{$this->PPVMin}\","; 
				$q .= "`PPVMax`=\"{$this->PPVMax}\","; 
				$q .= "`PPVComment`=\"{$this->PPVComment}\","; 
				$q .= "`NPVMin`=\"{$this->NPVMin}\","; 
				$q .= "`NPVMax`=\"{$this->NPVMax}\","; 
				$q .= "`NPVComment`=\"{$this->NPVComment}\","; 
				$q .= "`QAState`=\"{$this->QAState}\","; 
				$q .= "`Phase`=\"{$this->Phase}\","; 
				$q .= "`Description`=\"{$this->Description}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerOrganDataVars::ORGAN);
		$this->unlink(BiomarkerOrganDataVars::BIOMARKER);
		$this->unlink(BiomarkerOrganDataVars::RESOURCES);
		$this->unlink(BiomarkerOrganDataVars::PUBLICATIONS);
		$this->unlink(BiomarkerOrganDataVars::STUDYDATAS);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_BiomarkerOrganData WHERE `BiomarkerOrganDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganData_Organ WHERE `BiomarkerOrganDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganData_Resource WHERE `BiomarkerOrganDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganData_Publication WHERE `BiomarkerOrganDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE `BiomarkerOrganDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Study_BiomarkerOrganData WHERE `BiomarkerOrganDataID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM BiomarkerOrganData WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return BiomarkerOrganData::_TYPE; //BiomarkerOrganData
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarker":
				$test = "SELECT COUNT(*) FROM Biomarker WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerOrganData WHERE BiomarkerOrganDataID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerOrganData (BiomarkerOrganDataID,BiomarkerID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarker\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerOrganData SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "Organ":
				$test = "SELECT COUNT(*) FROM Organ WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Organ WHERE BiomarkerOrganDataID=$this->objId AND OrganID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Organ (BiomarkerOrganDataID,OrganID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',OrganVar').") VALUES($this->objId,$remoteID,\"Organ\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Organ SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', OrganVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND OrganID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$test = "SELECT COUNT(*) FROM Resource WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Resource WHERE BiomarkerOrganDataID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Resource (BiomarkerOrganDataID,ResourceID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Resource SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$test = "SELECT COUNT(*) FROM Publication WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Publication WHERE BiomarkerOrganDataID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Publication (BiomarkerOrganDataID,PublicationID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Publication SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "StudyDatas":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganStudyData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganDataID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_BiomarkerOrganStudyData (BiomarkerOrganDataID,BiomarkerOrganStudyDataID,BiomarkerOrganDataVar".(($remoteVar == '')? '' : ',BiomarkerOrganStudyDataVar').") VALUES($this->objId,$remoteID,\"StudyDatas\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_BiomarkerOrganStudyData SET BiomarkerOrganDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganStudyDataVar="{$remoteVar}" ')." WHERE BiomarkerOrganDataID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
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

/** BiomarkerOrganStudyData **/


class BiomarkerOrganStudyDataVars {
	const OBJID = "objId";
	const SENSITIVITY = "Sensitivity";
	const SPECIFICITY = "Specificity";
	const PPV = "PPV";
	const NPV = "NPV";
	const ASSAY = "Assay";
	const TECHNOLOGY = "Technology";
	const STUDY = "Study";
	const BIOMARKERORGANDATA = "BiomarkerOrganData";
	const PUBLICATIONS = "Publications";
	const RESOURCES = "Resources";
}

class BiomarkerOrganStudyDataFactory {
	public static function Create($StudyId,$BiomarkerOrganDataId){
		$o = new BiomarkerOrganStudyData();
		$o->save();
		$o->link(BiomarkerOrganStudyDataVars::STUDY,$StudyId,StudyVars::BIOMARKERORGANDATAS);
		$o->link(BiomarkerOrganStudyDataVars::BIOMARKERORGANDATA,$BiomarkerOrganDataId,BiomarkerOrganDataVars::STUDYDATAS);
		return $o;
	}
	public static function Retrieve($value,$key = BiomarkerOrganStudyDataVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new BiomarkerOrganStudyData();
		switch ($key) {
			case BiomarkerOrganStudyDataVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `BiomarkerOrganStudyData` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setSensitivity($data['Sensitivity'],false);
				$o->setSpecificity($data['Specificity'],false);
				$o->setPPV($data['PPV'],false);
				$o->setNPV($data['NPV'],false);
				$o->setAssay($data['Assay'],false);
				$o->setTechnology($data['Technology'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class BiomarkerOrganStudyData extends XPressObject {

	const _TYPE = "BiomarkerOrganStudyData";
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


	public function __construct($objId = 0) {
		//echo "creating object of type BiomarkerOrganStudyData<br/>";
		$this->objId = $objId;
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
		if ($this->Study != "") {
			return $this->Study;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::STUDY);
			return $this->Study;
		}
	}
	public function getBiomarkerOrganData() {
		if ($this->BiomarkerOrganData != "") {
			return $this->BiomarkerOrganData;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::BIOMARKERORGANDATA);
			return $this->BiomarkerOrganData;
		}
	}
	public function getPublications() {
		if ($this->Publications != array()) {
			return $this->Publications;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::PUBLICATIONS);
			return $this->Publications;
		}
	}
	public function getResources() {
		if ($this->Resources != array()) {
			return $this->Resources;
		} else {
			$this->inflate(BiomarkerOrganStudyDataVars::RESOURCES);
			return $this->Resources;
		}
	}

	// Mutator Functions 
	public function setSensitivity($value,$bSave = true) {
		$this->Sensitivity = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::SENSITIVITY);
		}
	}
	public function setSpecificity($value,$bSave = true) {
		$this->Specificity = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::SPECIFICITY);
		}
	}
	public function setPPV($value,$bSave = true) {
		$this->PPV = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::PPV);
		}
	}
	public function setNPV($value,$bSave = true) {
		$this->NPV = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::NPV);
		}
	}
	public function setAssay($value,$bSave = true) {
		$this->Assay = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::ASSAY);
		}
	}
	public function setTechnology($value,$bSave = true) {
		$this->Technology = $value;
		if ($bSave){
			$this->save(BiomarkerOrganStudyDataVars::TECHNOLOGY);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "BiomarkerOrganData":
				// Inflate "BiomarkerOrganData":
				$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"BiomarkerOrganData\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->BiomarkerOrganData = BiomarkerOrganDataFactory::retrieve($id[objId]);
				}
				break;
			case "Study":
				// Inflate "Study":
				$q = "SELECT StudyID AS objId FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"Study\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				foreach ($ids as $id) {
					$this->Study = StudyFactory::retrieve($id[objId]);
				}
				break;
			case "Publications":
				// Inflate "Publications":
				$q = "SELECT PublicationID AS objId FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"Publications\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Publications = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Publications[] = PublicationFactory::retrieve($id[objId]);
				}
				break;
			case "Resources":
				// Inflate "Resources":
				$q = "SELECT ResourceID AS objId FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID = {$this->objId} AND BiomarkerOrganStudyDataVar = \"Resources\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Resources = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Resources[] = ResourceFactory::retrieve($id[objId]);
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
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `BiomarkerOrganStudyData` ";
			$q .= 'VALUES("","'.$this->Sensitivity.'","'.$this->Specificity.'","'.$this->PPV.'","'.$this->NPV.'","'.$this->Assay.'","'.$this->Technology.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `BiomarkerOrganStudyData`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `BiomarkerOrganStudyData` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `BiomarkerOrganStudyData` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`Sensitivity`=\"{$this->Sensitivity}\","; 
				$q .= "`Specificity`=\"{$this->Specificity}\","; 
				$q .= "`PPV`=\"{$this->PPV}\","; 
				$q .= "`NPV`=\"{$this->NPV}\","; 
				$q .= "`Assay`=\"{$this->Assay}\","; 
				$q .= "`Technology`=\"{$this->Technology}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerOrganStudyDataVars::STUDY);
		$this->unlink(BiomarkerOrganStudyDataVars::BIOMARKERORGANDATA);
		$this->unlink(BiomarkerOrganStudyDataVars::PUBLICATIONS);
		$this->unlink(BiomarkerOrganStudyDataVars::RESOURCES);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE `BiomarkerOrganStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganStudyData_Study WHERE `BiomarkerOrganStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganStudyData_Publication WHERE `BiomarkerOrganStudyDataID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganStudyData_Resource WHERE `BiomarkerOrganStudyDataID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM BiomarkerOrganStudyData WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return BiomarkerOrganStudyData::_TYPE; //BiomarkerOrganStudyData
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "BiomarkerOrganData":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_BiomarkerOrganStudyData WHERE BiomarkerOrganStudyDataID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_BiomarkerOrganStudyData (BiomarkerOrganStudyDataID,BiomarkerOrganDataID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrganData\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_BiomarkerOrganStudyData SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "Study":
				$test = "SELECT COUNT(*) FROM Study WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Study WHERE BiomarkerOrganStudyDataID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Study (BiomarkerOrganStudyDataID,StudyID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Study\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Study SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
				break;
			case "Publications":
				$test = "SELECT COUNT(*) FROM Publication WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Publication WHERE BiomarkerOrganStudyDataID=$this->objId AND PublicationID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Publication (BiomarkerOrganStudyDataID,PublicationID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',PublicationVar').") VALUES($this->objId,$remoteID,\"Publications\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Publication SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PublicationVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND PublicationID=$remoteID LIMIT 1 ";
				break;
			case "Resources":
				$test = "SELECT COUNT(*) FROM Resource WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Resource WHERE BiomarkerOrganStudyDataID=$this->objId AND ResourceID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Resource (BiomarkerOrganStudyDataID,ResourceID,BiomarkerOrganStudyDataVar".(($remoteVar == '')? '' : ',ResourceVar').") VALUES($this->objId,$remoteID,\"Resources\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Resource SET BiomarkerOrganStudyDataVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', ResourceVar="{$remoteVar}" ')." WHERE BiomarkerOrganStudyDataID=$this->objId AND ResourceID=$remoteID LIMIT 1 ";
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

/** Publication **/


class PublicationVars {
	const OBJID = "objId";
	const ISPUBMED = "IsPubMed";
	const PUBMEDID = "PubMedID";
	const TITLE = "Title";
	const AUTHOR = "Author";
	const JOURNAL = "Journal";
	const VOLUME = "Volume";
	const ISSUE = "Issue";
	const YEAR = "Year";
	const BIOMARKERS = "Biomarkers";
	const BIOMARKERORGANS = "BiomarkerOrgans";
	const BIOMARKERORGANSTUDIES = "BiomarkerOrganStudies";
	const BIOMARKERSTUDIES = "BiomarkerStudies";
	const STUDIES = "Studies";
}

class PublicationFactory {
	public static function Create($PubMedID){
		$o = new Publication();
		$o->PubMedID = $PubMedID;
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = PublicationVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Publication();
		switch ($key) {
			case PublicationVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Publication` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setIsPubMed($data['IsPubMed'],false);
				$o->setPubMedID($data['PubMedID'],false);
				$o->setTitle($data['Title'],false);
				$o->setAuthor($data['Author'],false);
				$o->setJournal($data['Journal'],false);
				$o->setVolume($data['Volume'],false);
				$o->setIssue($data['Issue'],false);
				$o->setYear($data['Year'],false);
				return $o;
				break;
			case PublicationVars::PUBMEDID:
				$o->setPubMedID($value,false);
				$q = "SELECT * FROM `Publication` WHERE `PubMedID`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) { return false;}
				if (! is_array($data)) {return false;}
				$o->setobjId($data['objId'],false);
				$o->setIsPubMed($data['IsPubMed'],false);
				$o->setPubMedID($data['PubMedID'],false);
				$o->setTitle($data['Title'],false);
				$o->setAuthor($data['Author'],false);
				$o->setJournal($data['Journal'],false);
				$o->setVolume($data['Volume'],false);
				$o->setIssue($data['Issue'],false);
				$o->setYear($data['Year'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Publication extends XPressObject {

	const _TYPE = "Publication";
	public $IsPubMed = '';
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


	public function __construct($objId = 0) {
		//echo "creating object of type Publication<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getIsPubMed() {
		 return $this->IsPubMed;
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
		if ($this->Biomarkers != array()) {
			return $this->Biomarkers;
		} else {
			$this->inflate(PublicationVars::BIOMARKERS);
			return $this->Biomarkers;
		}
	}
	public function getBiomarkerOrgans() {
		if ($this->BiomarkerOrgans != array()) {
			return $this->BiomarkerOrgans;
		} else {
			$this->inflate(PublicationVars::BIOMARKERORGANS);
			return $this->BiomarkerOrgans;
		}
	}
	public function getBiomarkerOrganStudies() {
		if ($this->BiomarkerOrganStudies != array()) {
			return $this->BiomarkerOrganStudies;
		} else {
			$this->inflate(PublicationVars::BIOMARKERORGANSTUDIES);
			return $this->BiomarkerOrganStudies;
		}
	}
	public function getBiomarkerStudies() {
		if ($this->BiomarkerStudies != array()) {
			return $this->BiomarkerStudies;
		} else {
			$this->inflate(PublicationVars::BIOMARKERSTUDIES);
			return $this->BiomarkerStudies;
		}
	}
	public function getStudies() {
		if ($this->Studies != array()) {
			return $this->Studies;
		} else {
			$this->inflate(PublicationVars::STUDIES);
			return $this->Studies;
		}
	}

	// Mutator Functions 
	public function setIsPubMed($value,$bSave = true) {
		$this->IsPubMed = $value;
		if ($bSave){
			$this->save(PublicationVars::ISPUBMED);
		}
	}
	public function setPubMedID($value,$bSave = true) {
		$this->PubMedID = $value;
		if ($bSave){
			$this->save(PublicationVars::PUBMEDID);
		}
	}
	public function setTitle($value,$bSave = true) {
		$this->Title = $value;
		if ($bSave){
			$this->save(PublicationVars::TITLE);
		}
	}
	public function setAuthor($value,$bSave = true) {
		$this->Author = $value;
		if ($bSave){
			$this->save(PublicationVars::AUTHOR);
		}
	}
	public function setJournal($value,$bSave = true) {
		$this->Journal = $value;
		if ($bSave){
			$this->save(PublicationVars::JOURNAL);
		}
	}
	public function setVolume($value,$bSave = true) {
		$this->Volume = $value;
		if ($bSave){
			$this->save(PublicationVars::VOLUME);
		}
	}
	public function setIssue($value,$bSave = true) {
		$this->Issue = $value;
		if ($bSave){
			$this->save(PublicationVars::ISSUE);
		}
	}
	public function setYear($value,$bSave = true) {
		$this->Year = $value;
		if ($bSave){
			$this->save(PublicationVars::YEAR);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Biomarkers":
				// Inflate "Biomarkers":
				$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"Biomarkers\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Biomarkers = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Biomarkers[] = BiomarkerFactory::retrieve($id[objId]);
				}
				break;
			case "BiomarkerStudies":
				// Inflate "BiomarkerStudies":
				$q = "SELECT BiomarkerStudyDataID AS objId FROM xr_BiomarkerStudyData_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"BiomarkerStudies\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->BiomarkerStudies = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->BiomarkerStudies[] = BiomarkerStudyDataFactory::retrieve($id[objId]);
				}
				break;
			case "BiomarkerOrgans":
				// Inflate "BiomarkerOrgans":
				$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"BiomarkerOrgans\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->BiomarkerOrgans = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->BiomarkerOrgans[] = BiomarkerOrganDataFactory::retrieve($id[objId]);
				}
				break;
			case "BiomarkerOrganStudies":
				// Inflate "BiomarkerOrganStudies":
				$q = "SELECT BiomarkerOrganStudyDataID AS objId FROM xr_BiomarkerOrganStudyData_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"BiomarkerOrganStudies\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->BiomarkerOrganStudies = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->BiomarkerOrganStudies[] = BiomarkerOrganStudyDataFactory::retrieve($id[objId]);
				}
				break;
			case "Studies":
				// Inflate "Studies":
				$q = "SELECT StudyID AS objId FROM xr_Study_Publication WHERE PublicationID = {$this->objId} AND PublicationVar = \"Studies\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Studies = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Studies[] = StudyFactory::retrieve($id[objId]);
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
		$this->IsPubMed = '';
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
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Publication` ";
			$q .= 'VALUES("","'.$this->IsPubMed.'","'.$this->PubMedID.'","'.$this->Title.'","'.$this->Author.'","'.$this->Journal.'","'.$this->Volume.'","'.$this->Issue.'","'.$this->Year.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Publication`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Publication` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Publication` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`IsPubMed`=\"{$this->IsPubMed}\","; 
				$q .= "`PubMedID`=\"{$this->PubMedID}\","; 
				$q .= "`Title`=\"{$this->Title}\","; 
				$q .= "`Author`=\"{$this->Author}\","; 
				$q .= "`Journal`=\"{$this->Journal}\","; 
				$q .= "`Volume`=\"{$this->Volume}\","; 
				$q .= "`Issue`=\"{$this->Issue}\","; 
				$q .= "`Year`=\"{$this->Year}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(PublicationVars::BIOMARKERS);
		$this->unlink(PublicationVars::BIOMARKERORGANS);
		$this->unlink(PublicationVars::BIOMARKERORGANSTUDIES);
		$this->unlink(PublicationVars::BIOMARKERSTUDIES);
		$this->unlink(PublicationVars::STUDIES);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_Publication WHERE `PublicationID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerStudyData_Publication WHERE `PublicationID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganData_Publication WHERE `PublicationID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganStudyData_Publication WHERE `PublicationID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Study_Publication WHERE `PublicationID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Publication WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Publication::_TYPE; //Publication
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarkers":
				$test = "SELECT COUNT(*) FROM Biomarker WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Publication WHERE PublicationID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_Publication (PublicationID,BiomarkerID,PublicationVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarkers\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerStudies":
				$test = "SELECT COUNT(*) FROM BiomarkerStudyData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Publication WHERE PublicationID=$this->objId AND BiomarkerStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Publication (PublicationID,BiomarkerStudyDataID,PublicationVar".(($remoteVar == '')? '' : ',BiomarkerStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerStudies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerStudyDataVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrgans":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Publication WHERE PublicationID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Publication (PublicationID,BiomarkerOrganDataID,PublicationVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrgans\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrganStudies":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganStudyData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Publication WHERE PublicationID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Publication (PublicationID,BiomarkerOrganStudyDataID,PublicationVar".(($remoteVar == '')? '' : ',BiomarkerOrganStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrganStudies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganStudyDataVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "Studies":
				$test = "SELECT COUNT(*) FROM Study WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Study_Publication WHERE PublicationID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_Publication (PublicationID,StudyID,PublicationVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Studies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_Publication SET PublicationVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE PublicationID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
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

/** Resource **/


class ResourceVars {
	const OBJID = "objId";
	const NAME = "Name";
	const URL = "URL";
	const BIOMARKERS = "Biomarkers";
	const BIOMARKERORGANS = "BiomarkerOrgans";
	const BIOMARKERORGANSTUDIES = "BiomarkerOrganStudies";
	const BIOMARKERSTUDIES = "BiomarkerStudies";
	const STUDIES = "Studies";
}

class ResourceFactory {
	public static function Create(){
		$o = new Resource();
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = ResourceVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Resource();
		switch ($key) {
			case ResourceVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Resource` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setName($data['Name'],false);
				$o->setURL($data['URL'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Resource extends XPressObject {

	const _TYPE = "Resource";
	public $Name = '';
	public $URL = '';
	public $Biomarkers = array();
	public $BiomarkerOrgans = array();
	public $BiomarkerOrganStudies = array();
	public $BiomarkerStudies = array();
	public $Studies = array();


	public function __construct($objId = 0) {
		//echo "creating object of type Resource<br/>";
		$this->objId = $objId;
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
		if ($this->Biomarkers != array()) {
			return $this->Biomarkers;
		} else {
			$this->inflate(ResourceVars::BIOMARKERS);
			return $this->Biomarkers;
		}
	}
	public function getBiomarkerOrgans() {
		if ($this->BiomarkerOrgans != array()) {
			return $this->BiomarkerOrgans;
		} else {
			$this->inflate(ResourceVars::BIOMARKERORGANS);
			return $this->BiomarkerOrgans;
		}
	}
	public function getBiomarkerOrganStudies() {
		if ($this->BiomarkerOrganStudies != array()) {
			return $this->BiomarkerOrganStudies;
		} else {
			$this->inflate(ResourceVars::BIOMARKERORGANSTUDIES);
			return $this->BiomarkerOrganStudies;
		}
	}
	public function getBiomarkerStudies() {
		if ($this->BiomarkerStudies != array()) {
			return $this->BiomarkerStudies;
		} else {
			$this->inflate(ResourceVars::BIOMARKERSTUDIES);
			return $this->BiomarkerStudies;
		}
	}
	public function getStudies() {
		if ($this->Studies != array()) {
			return $this->Studies;
		} else {
			$this->inflate(ResourceVars::STUDIES);
			return $this->Studies;
		}
	}

	// Mutator Functions 
	public function setName($value,$bSave = true) {
		$this->Name = $value;
		if ($bSave){
			$this->save(ResourceVars::NAME);
		}
	}
	public function setURL($value,$bSave = true) {
		$this->URL = $value;
		if ($bSave){
			$this->save(ResourceVars::URL);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Biomarkers":
				// Inflate "Biomarkers":
				$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"Biomarkers\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Biomarkers = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Biomarkers[] = BiomarkerFactory::retrieve($id[objId]);
				}
				break;
			case "BiomarkerStudies":
				// Inflate "BiomarkerStudies":
				$q = "SELECT BiomarkerStudyDataID AS objId FROM xr_BiomarkerStudyData_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"BiomarkerStudies\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->BiomarkerStudies = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->BiomarkerStudies[] = BiomarkerStudyDataFactory::retrieve($id[objId]);
				}
				break;
			case "BiomarkerOrgans":
				// Inflate "BiomarkerOrgans":
				$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"BiomarkerOrgans\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->BiomarkerOrgans = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->BiomarkerOrgans[] = BiomarkerOrganDataFactory::retrieve($id[objId]);
				}
				break;
			case "BiomarkerOrganStudies":
				// Inflate "BiomarkerOrganStudies":
				$q = "SELECT BiomarkerOrganStudyDataID AS objId FROM xr_BiomarkerOrganStudyData_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"BiomarkerOrganStudies\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->BiomarkerOrganStudies = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->BiomarkerOrganStudies[] = BiomarkerOrganStudyDataFactory::retrieve($id[objId]);
				}
				break;
			case "Studies":
				// Inflate "Studies":
				$q = "SELECT StudyID AS objId FROM xr_Study_Resource WHERE ResourceID = {$this->objId} AND ResourceVar = \"Studies\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Studies = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Studies[] = StudyFactory::retrieve($id[objId]);
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
		$this->Name = '';
		$this->URL = '';
		$this->Biomarkers = array();
		$this->BiomarkerOrgans = array();
		$this->BiomarkerOrganStudies = array();
		$this->BiomarkerStudies = array();
		$this->Studies = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Resource` ";
			$q .= 'VALUES("","'.$this->Name.'","'.$this->URL.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Resource`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Resource` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Resource` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`Name`=\"{$this->Name}\","; 
				$q .= "`URL`=\"{$this->URL}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(ResourceVars::BIOMARKERS);
		$this->unlink(ResourceVars::BIOMARKERORGANS);
		$this->unlink(ResourceVars::BIOMARKERORGANSTUDIES);
		$this->unlink(ResourceVars::BIOMARKERSTUDIES);
		$this->unlink(ResourceVars::STUDIES);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Biomarker_Resource WHERE `ResourceID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerStudyData_Resource WHERE `ResourceID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganData_Resource WHERE `ResourceID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_BiomarkerOrganStudyData_Resource WHERE `ResourceID`={$this->objId}");
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Study_Resource WHERE `ResourceID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Resource WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Resource::_TYPE; //Resource
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarkers":
				$test = "SELECT COUNT(*) FROM Biomarker WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Biomarker_Resource WHERE ResourceID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_Resource (ResourceID,BiomarkerID,ResourceVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarkers\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerStudies":
				$test = "SELECT COUNT(*) FROM BiomarkerStudyData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerStudyData_Resource WHERE ResourceID=$this->objId AND BiomarkerStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerStudyData_Resource (ResourceID,BiomarkerStudyDataID,ResourceVar".(($remoteVar == '')? '' : ',BiomarkerStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerStudies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerStudyData_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerStudyDataVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND BiomarkerStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrgans":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Resource WHERE ResourceID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Resource (ResourceID,BiomarkerOrganDataID,ResourceVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrgans\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				break;
			case "BiomarkerOrganStudies":
				$test = "SELECT COUNT(*) FROM BiomarkerOrganStudyData WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganStudyData_Resource WHERE ResourceID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganStudyData_Resource (ResourceID,BiomarkerOrganStudyDataID,ResourceVar".(($remoteVar == '')? '' : ',BiomarkerOrganStudyDataVar').") VALUES($this->objId,$remoteID,\"BiomarkerOrganStudies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganStudyData_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganStudyDataVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND BiomarkerOrganStudyDataID=$remoteID LIMIT 1 ";
				break;
			case "Studies":
				$test = "SELECT COUNT(*) FROM Study WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Study_Resource WHERE ResourceID=$this->objId AND StudyID=$remoteID ";
				$q0 = "INSERT INTO xr_Study_Resource (ResourceID,StudyID,ResourceVar".(($remoteVar == '')? '' : ',StudyVar').") VALUES($this->objId,$remoteID,\"Studies\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Study_Resource SET ResourceVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', StudyVar="{$remoteVar}" ')." WHERE ResourceID=$this->objId AND StudyID=$remoteID LIMIT 1 ";
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

/** Site **/


class SiteVars {
	const OBJID = "objId";
	const NAME = "Name";
	const STAFF = "Staff";
}

class SiteFactory {
	public static function Create(){
		$o = new Site();
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = SiteVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Site();
		switch ($key) {
			case SiteVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Site` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setName($data['Name'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Site extends XPressObject {

	const _TYPE = "Site";
	public $Name = '';
	public $Staff = array();


	public function __construct($objId = 0) {
		//echo "creating object of type Site<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getName() {
		 return $this->Name;
	}
	public function getStaff() {
		if ($this->Staff != array()) {
			return $this->Staff;
		} else {
			$this->inflate(SiteVars::STAFF);
			return $this->Staff;
		}
	}

	// Mutator Functions 
	public function setName($value,$bSave = true) {
		$this->Name = $value;
		if ($bSave){
			$this->save(SiteVars::NAME);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Staff":
				// Inflate "Staff":
				$q = "SELECT PersonID AS objId FROM xr_Person_Site WHERE SiteID = {$this->objId} AND SiteVar = \"Staff\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Staff = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Staff[] = PersonFactory::retrieve($id[objId]);
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
		$this->Name = '';
		$this->Staff = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Site` ";
			$q .= 'VALUES("","'.$this->Name.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Site`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Site` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Site` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`Name`=\"{$this->Name}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(SiteVars::STAFF);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Person_Site WHERE `SiteID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Site WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Site::_TYPE; //Site
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Staff":
				$test = "SELECT COUNT(*) FROM Person WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE SiteID=$this->objId AND PersonID=$remoteID ";
				$q0 = "INSERT INTO xr_Person_Site (SiteID,PersonID,SiteVar".(($remoteVar == '')? '' : ',PersonVar').") VALUES($this->objId,$remoteID,\"Staff\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Person_Site SET SiteVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PersonVar="{$remoteVar}" ')." WHERE SiteID=$this->objId AND PersonID=$remoteID LIMIT 1 ";
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
			case "Staff":
				$q = "DELETE FROM xr_Person_Site WHERE SiteID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PersonID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND SiteVar = \"Staff\" ";
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

/** Person **/


class PersonVars {
	const OBJID = "objId";
	const FIRSTNAME = "FirstName";
	const LASTNAME = "LastName";
	const TITLE = "Title";
	const SPECIALTY = "Specialty";
	const PHONE = "Phone";
	const FAX = "Fax";
	const EMAIL = "Email";
	const SITE = "Site";
}

class PersonFactory {
	public static function Create(){
		$o = new Person();
		$o->save();
		return $o;
	}
	public static function Retrieve($value,$key = PersonVars::OBJID,$fetchStrategy = XPress::FETCH_LOCAL) {
		$o = new Person();
		switch ($key) {
			case PersonVars::OBJID:
				$o->objId = $value;
				$q = "SELECT * FROM `Person` WHERE `objId`=\"{$value}\" LIMIT 1";
				$data = XPress::getInstance()->getDatabase()->getRow($q);
				if (DB::isError($data)) {return false;}
				if (! is_array($data)) {return false;}
				$o->setFirstName($data['FirstName'],false);
				$o->setLastName($data['LastName'],false);
				$o->setTitle($data['Title'],false);
				$o->setSpecialty($data['Specialty'],false);
				$o->setPhone($data['Phone'],false);
				$o->setFax($data['Fax'],false);
				$o->setEmail($data['Email'],false);
				return $o;
				break;
			default:
				return false;
				break;
		}
	}
}

class Person extends XPressObject {

	const _TYPE = "Person";
	public $FirstName = '';
	public $LastName = '';
	public $Title = '';
	public $Specialty = '';
	public $Phone = '';
	public $Fax = '';
	public $Email = '';
	public $Site = array();


	public function __construct($objId = 0) {
		//echo "creating object of type Person<br/>";
		$this->objId = $objId;
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getFirstName() {
		 return $this->FirstName;
	}
	public function getLastName() {
		 return $this->LastName;
	}
	public function getTitle() {
		 return $this->Title;
	}
	public function getSpecialty() {
		 return $this->Specialty;
	}
	public function getPhone() {
		 return $this->Phone;
	}
	public function getFax() {
		 return $this->Fax;
	}
	public function getEmail() {
		 return $this->Email;
	}
	public function getSite() {
		if ($this->Site != array()) {
			return $this->Site;
		} else {
			$this->inflate(PersonVars::SITE);
			return $this->Site;
		}
	}

	// Mutator Functions 
	public function setFirstName($value,$bSave = true) {
		$this->FirstName = $value;
		if ($bSave){
			$this->save(PersonVars::FIRSTNAME);
		}
	}
	public function setLastName($value,$bSave = true) {
		$this->LastName = $value;
		if ($bSave){
			$this->save(PersonVars::LASTNAME);
		}
	}
	public function setTitle($value,$bSave = true) {
		$this->Title = $value;
		if ($bSave){
			$this->save(PersonVars::TITLE);
		}
	}
	public function setSpecialty($value,$bSave = true) {
		$this->Specialty = $value;
		if ($bSave){
			$this->save(PersonVars::SPECIALTY);
		}
	}
	public function setPhone($value,$bSave = true) {
		$this->Phone = $value;
		if ($bSave){
			$this->save(PersonVars::PHONE);
		}
	}
	public function setFax($value,$bSave = true) {
		$this->Fax = $value;
		if ($bSave){
			$this->save(PersonVars::FAX);
		}
	}
	public function setEmail($value,$bSave = true) {
		$this->Email = $value;
		if ($bSave){
			$this->save(PersonVars::EMAIL);
		}
	}

	// API
	private function inflate($variableName) {
		switch ($variableName) {
			case "Site":
				// Inflate "Site":
				$q = "SELECT SiteID AS objId FROM xr_Person_Site WHERE PersonID = {$this->objId} AND PersonVar = \"Site\" ";
				$ids = XPress::getInstance()->getDatabase()->getAll($q);
				$this->Site = array(); // reset before repopulation to avoid dups
				foreach ($ids as $id) {
					$this->Site[] = SiteFactory::retrieve($id[objId]);
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
		$this->FirstName = '';
		$this->LastName = '';
		$this->Title = '';
		$this->Specialty = '';
		$this->Phone = '';
		$this->Fax = '';
		$this->Email = '';
		$this->Site = array();
	}
	public function save($attr = null) {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Person` ";
			$q .= 'VALUES("","'.$this->FirstName.'","'.$this->LastName.'","'.$this->Title.'","'.$this->Specialty.'","'.$this->Phone.'","'.$this->Fax.'","'.$this->Email.'") ';
			$r = XPress::getInstance()->getDatabase()->query($q);
			$this->objId = XPress::getInstance()->getDatabase()->getOne("SELECT LAST_INSERT_ID() FROM `Person`");
		} else {
			if ($attr != null) {
				// Update the given field of an existing object in the db
				$q = "UPDATE `Person` SET `{$attr}`=\"{$this->$attr}\" WHERE `objId` = $this->objId";
			} else {
				// Update all fields of an existing object in the db
				$q = "UPDATE `Person` SET ";
				$q .= "`objId`=\"{$this->objId}\","; 
				$q .= "`FirstName`=\"{$this->FirstName}\","; 
				$q .= "`LastName`=\"{$this->LastName}\","; 
				$q .= "`Title`=\"{$this->Title}\","; 
				$q .= "`Specialty`=\"{$this->Specialty}\","; 
				$q .= "`Phone`=\"{$this->Phone}\","; 
				$q .= "`Fax`=\"{$this->Fax}\","; 
				$q .= "`Email`=\"{$this->Email}\" ";
				$q .= "WHERE `objId` = $this->objId ";
			}
			$r = XPress::getInstance()->getDatabase()->query($q);
		}
	}
	public function delete() {
		//Delete this object's child objects

		//Intelligently unlink this object from any other objects
		$this->unlink(PersonVars::SITE);

		//Signal objects that link to this object to unlink
		// (this covers the case in which a relationship is only 1-directional, where
		// this object has no idea its being pointed to by something)
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM xr_Person_Site WHERE `PersonID`={$this->objId}");

		//Delete object from the database
		$r = XPress::getInstance()->getDatabase()->query("DELETE FROM Person WHERE `objId` = $this->objId ");
		$this->deflate();
	}
	public function _getType(){
		return Person::_TYPE; //Person
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Site":
				$test = "SELECT COUNT(*) FROM Site WHERE objId=\"{$remoteID}\" ";
 				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE PersonID=$this->objId AND SiteID=$remoteID ";
				$q0 = "INSERT INTO xr_Person_Site (PersonID,SiteID,PersonVar".(($remoteVar == '')? '' : ',SiteVar').") VALUES($this->objId,$remoteID,\"Site\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Person_Site SET PersonVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', SiteVar="{$remoteVar}" ')." WHERE PersonID=$this->objId AND SiteID=$remoteID LIMIT 1 ";
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
			case "Site":
				$q = "DELETE FROM xr_Person_Site WHERE PersonID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND SiteID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PersonVar = \"Site\" ";
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