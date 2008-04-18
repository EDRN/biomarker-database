<?php


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

?>