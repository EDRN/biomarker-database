<?php
class BiomarkerAlias {

	public static function Create() {
		$vo = new voBiomarkerAlias();
		$dao = new daoBiomarkerAlias();
		$dao->insert(&$vo);
		$obj = new objBiomarkerAlias();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoBiomarkerAlias();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchBiomarkerAliasException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objBiomarkerAlias();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->Biomarker = '';
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->Biomarker = BiomarkerAliasXref::retrieve($o,"Biomarker",$po,$lazyFetch,$limit,"Biomarker");
						if ($o->Biomarker == null){$o->Biomarker = '';}
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
		$dao = new daoBiomarkerAlias();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchBiomarkerAliasException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objBiomarkerAlias;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Biomarker = '';
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Biomarker = BiomarkerAliasXref::retrieve($obj,"Biomarker",array($obj),$lazyFetch,1,"Biomarker");
			if ($obj->Biomarker == null){$obj->Biomarker = '';}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerAlias` WHERE BiomarkerAliasID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `BiomarkerAlias` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_Biomarker_BiomarkerAlias` WHERE BiomarkerAliasID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `BiomarkerAlias` WHERE ID = $objID");
	}
	public static function Exists() {
		$dao = new daoBiomarkerAlias();
		try {
			$dao->getBy(array(),array());
		} catch (NoSuchBiomarkerAliasException $e){
			return false;
		}
		return true;
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoBiomarkerAlias;
		$dao->save(&$vo);
	}
	public static function attach_Biomarker($object,$Biomarker){
		$object->Biomarker = $Biomarker;
	}
}

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
		$json = '{';
		$json .= "\"objId\": \"{$this->objId}\", ";
		$json .= "\"Alias\": \"{$this->Alias}\", ";
		$json .= "\"Biomarker\": ".(($this->getBiomarker() != null)? $this->getBiomarker()->toJSON() : "{}").",";
		$json .= "\"_objectType\": \"BiomarkerAlias\"}";
		return ($json);
	}
	public function associate($objectID,$variableName) {
		switch ($variableName) {
			case "Biomarker":
				BiomarkerAliasXref::createByIDs($this->ID,"Biomarker",$objectID,"Biomarker");
				break;
			default: 
				return false;
		}
		return true;
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

class voBiomarkerAlias {
	public $objId;
	public $Alias;

	public function toAssocArray(){
		return array(
			"objId" => $this->objId,
			"Alias" => $this->Alias,
		);
	}
	public function applyAssocArray($arr) {
		if(!empty($arr['objId'])){
			$this->objId = $arr['objId'];
		}
		if(!empty($arr['Alias'])){
			$this->Alias = $arr['Alias'];
		}
	}
}

class daoBiomarkerAlias {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `BiomarkerAlias` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchBiomarkerAliasException("No BiomarkerAlias found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerAlias();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `BiomarkerAlias` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerAlias();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `BiomarkerAlias` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voBiomarkerAlias();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `BiomarkerAlias`"; 
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
		$q = "DELETE FROM `BiomarkerAlias` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `BiomarkerAlias` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "Alias=\"$vo->Alias\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `BiomarkerAlias` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->Alias.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `BiomarkerAlias`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->Alias = $result['Alias'];
	}

}

class NoSuchBiomarkerAliasException extends Exception {
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

class BiomarkerAliasXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "Biomarker":
				$q  = "SELECT COUNT(*) FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerAliasID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Biomarker_BiomarkerAlias (BiomarkerAliasID,BiomarkerID,BiomarkerAliasVar) VALUES($localID,$remoteID,\"Biomarker\");";
				$q1 = "UPDATE xr_Biomarker_BiomarkerAlias SET BiomarkerAliasVar=\"{$variableName}\" WHERE BiomarkerAliasID=$localID AND BiomarkerID=$remoteID LIMIT 1 ";
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
				$q = "DELETE FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerAliasID = $localID AND BiomarkerID = $remoteID AND BiomarkerAliasVar = \"Biomarker\" LIMIT 1";
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
				$q = "SELECT BiomarkerID AS ID FROM xr_Biomarker_BiomarkerAlias WHERE BiomarkerAliasID = {$local->ID} AND BiomarkerAliasVar = \"Biomarker\" ";
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
				$q = "DELETE FROM `xr_Biomarker_BiomarkerAlias` WHERE BiomarkerAliasID = $objectID AND BiomarkerAliasVar = \"Biomarker\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class BiomarkerAliasActions {
	public static function associateBiomarker($BiomarkerAliasID,$BiomarkerID){
		BiomarkerAliasXref::purgeVariable($BiomarkerAliasID,"Biomarker");
		BiomarkerAliasXref::createByIDs($BiomarkerAliasID,"Biomarker",$BiomarkerID,"Biomarker");
	}
	public static function dissociateBiomarker($BiomarkerAliasID,$BiomarkerID){
		BiomarkerAliasXref::deleteByIDs($BiomarkerAliasID,"Biomarker",$BiomarkerID,"Biomarker");
	}
}

?>