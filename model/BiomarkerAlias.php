<?php
class BiomarkerAliasVars {
	const BIO_OBJID = "objId";
	const BIO_ALIAS = "Alias";
	const BIO_BIOMARKER = "Biomarker";
}

class objBiomarkerAlias {

	const _TYPE = "BiomarkerAlias";
	private $XPress;
	public $objId = '';
	public $Alias = '';
	public $Biomarker = '';


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type BiomarkerAlias<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		if ($objId == 0) { return false; /* must not be zero */ }
		$this->objId = $objId;
		$q = "SELECT * FROM `BiomarkerAlias` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->Alias = $result['Alias'];
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
		$this->Alias = '';
		$this->Biomarker = '';
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getAlias() {
		 return $this->Alias;
	}
	public function getBiomarker() {
		 return $this->Biomarker;
	}

	// Mutator Functions 
	public function setObjId($value,$bSave = true) {
		$this->objId = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setAlias($value,$bSave = true) {
		$this->Alias = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function create($BiomarkerId){
		$this->save();
		$this->link("Biomarker",$BiomarkerId,"Aliases");
	}
	public function inflate($parentObjects = array()) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "Biomarker":
		$q = "SELECT BiomarkerID AS objId FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerAliasID = {$this->objId} AND BiomarkerAliasVar = \"Biomarker\" ";
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
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `BiomarkerAlias` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->Alias.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `BiomarkerAlias`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `BiomarkerAlias` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`Alias`=\"$this->Alias\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(BiomarkerAliasVars::BIO_BIOMARKER);
		//Delete this object's child objects

		//Delete object from the database
		$q = "DELETE FROM `BiomarkerAlias` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "BiomarkerAlias";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Biomarker":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerAliasID=$this->objId AND BiomarkerID=$remoteID ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerAlias (BiomarkerAliasID,BiomarkerID,BiomarkerAliasVar".(($remoteVar == '')? '' : ',BiomarkerVar').") VALUES($this->objId,$remoteID,\"Biomarker\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerAlias SET BiomarkerAliasVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerVar="{$remoteVar}" ')." WHERE BiomarkerAliasID=$this->objId AND BiomarkerID=$remoteID LIMIT 1 ";
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
				$q = "DELETE FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerAliasID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND BiomarkerAliasVar = \"Biomarker\" ";
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
			case "Biomarker":
				BiomarkerAliasXref::deleteByIDs($this->ID,"Biomarker",$objectID,"Biomarker");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voBiomarkerAlias();
		$vo->objId = $this->objId;
		$vo->Alias = $this->Alias;
		return $vo;
	}
	public function applyVO($voBiomarkerAlias) {
		if(!empty($voBiomarkerAlias->objId)){
			$this->objId = $voBiomarkerAlias->objId;
		}
		if(!empty($voBiomarkerAlias->Alias)){
			$this->Alias = $voBiomarkerAlias->Alias;
		}
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}
}

?>