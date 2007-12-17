<?php
class Site {

	public static function Create() {
		$vo = new voSite();
		$dao = new daoSite();
		$dao->insert(&$vo);
		$obj = new objSite();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoSite();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchSiteException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objSite();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->Staff = array();
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->Staff = SiteXref::retrieve($o,"Person",$po,$lazyFetch,$limit,"Staff");
						if ($o->Staff == null){$o->Staff = array();}
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
		$dao = new daoSite();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchSiteException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objSite;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Staff = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Staff = SiteXref::retrieve($obj,"Person",array($obj),$lazyFetch,$limit,"Staff");
			if ($obj->Staff == null){$obj->Staff = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_Person_Site` WHERE SiteID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `Site` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_Person_Site` WHERE SiteID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `Site` WHERE ID = $objID");
	}
	public static function Exists() {
		$dao = new daoSite();
		try {
			$dao->getBy(array(),array());
		} catch (NoSuchSiteException $e){
			return false;
		}
		return true;
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoSite;
		$dao->save(&$vo);
	}
	public static function attach_Staff($object,$Person){
		$object->Staff[] = $Person;
	}
}

class SiteVars {
	const SIT_OBJID = "objId";
	const SIT_NAME = "Name";
	const SIT_STAFF = "Staff";
}

class objSite {

	const _TYPE = "Site";
	private $XPress;
	public $objId = '';
	public $Name = '';
	public $Staff = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Site<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		$this->objId = $objId;
		$q = "SELECT * FROM `Site` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->Name = $result['Name'];
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
		$this->Name = '';
		$this->Staff = array();
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getName() {
		 return $this->Name;
	}
	public function getStaff() {
		 return $this->Staff;
	}

	// Mutator Functions 
	public function setObjId($value,$bSave = true) {
		$this->objId = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setName($value,$bSave = true) {
		$this->Name = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function create(){
		$this->save();
	}
	public function inflate($parentObjects = array()) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "Staff":
		$q = "SELECT PersonID AS objId FROM xr_Person_Site WHERE SiteID = {$this->objId} AND SiteVar = \"Staff\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objPerson($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Staff[] = $obj;
			}
			$rcount++;
		}
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Site` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->Name.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Site`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Site` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`Name`=\"$this->Name\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(SiteVars::SIT_STAFF);
		//Delete object from the database
		$q = "DELETE FROM `Site` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Site";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Staff":
				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE SiteID=$this->objId AND PersonID=$remoteID ";
				$q0 = "INSERT INTO xr_Person_Site (SiteID,PersonID,SiteVar".(($remoteVar == '')? '' : ',PersonVar').") VALUES($this->objId,$remoteID,\"Staff\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Person_Site SET SiteVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', PersonVar="{$remoteVar}" ')." WHERE SiteID=$this->objId AND PersonID=$remoteID LIMIT 1 ";
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
			case "Staff":
				$q = "DELETE FROM xr_Person_Site WHERE SiteID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND PersonID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND SiteVar = \"Staff\" ";
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
		$json .= "\"Name\": \"{$this->Name}\", ";
		$json .= "\"Staff\": [";
		$jsonSnippets = array();
		foreach ($this->Staff as $var){
			$jsonSnippets[] = $var->toJSON();
		}
		$json .= implode(",",$jsonSnippets);
		$json .= "], ";
		$json .= "\"_objectType\": \"Site\"}";
		return ($json);
	}
	public function associate($objectID,$variableName) {
		switch ($variableName) {
			case "Staff":
				SiteXref::createByIDs($this->ID,"Person",$objectID,"Staff");
				break;
			default: 
				return false;
		}
		return true;
	}
	public function dissociate($objectID,$variableName) {
		switch ($variableName) {
			case "Staff":
				SiteXref::deleteByIDs($this->ID,"Person",$objectID,"Staff");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voSite();
		$vo->objId = $this->objId;
		$vo->Name = $this->Name;
		return $vo;
	}
	public function applyVO($voSite) {
		if(!empty($voSite->objId)){
			$this->objId = $voSite->objId;
		}
		if(!empty($voSite->Name)){
			$this->Name = $voSite->Name;
		}
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}
}

class voSite {
	public $objId;
	public $Name;

	public function toAssocArray(){
		return array(
			"objId" => $this->objId,
			"Name" => $this->Name,
		);
	}
	public function applyAssocArray($arr) {
		if(!empty($arr['objId'])){
			$this->objId = $arr['objId'];
		}
		if(!empty($arr['Name'])){
			$this->Name = $arr['Name'];
		}
	}
}

class daoSite {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `Site` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchSiteException("No Site found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voSite();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Site` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voSite();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `Site` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voSite();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Site`"; 
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
		$q = "DELETE FROM `Site` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `Site` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "Name=\"$vo->Name\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Site` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->Name.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Site`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->Name = $result['Name'];
	}

}

class NoSuchSiteException extends Exception {
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

class SiteXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "Staff":
				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE SiteID=$localID AND PersonID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Person_Site (SiteID,PersonID,SiteVar) VALUES($localID,$remoteID,\"Staff\");";
				$q1 = "UPDATE xr_Person_Site SET SiteVar=\"{$variableName}\" WHERE SiteID=$localID AND PersonID=$remoteID LIMIT 1 ";
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
			case "Staff":
				$q = "DELETE FROM xr_Person_Site WHERE SiteID = $localID AND PersonID = $remoteID AND SiteVar = \"Staff\" LIMIT 1";
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
			case "Staff":
				$q = "SELECT PersonID AS ID FROM xr_Person_Site WHERE SiteID = {$local->ID} AND SiteVar = \"Staff\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = Person::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
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
			case "Staff":
				$q = "DELETE FROM `xr_Person_Site` WHERE SiteID = $objectID AND SiteVar = \"Staff\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class SiteActions {
	public static function associateStaff($SiteID,$PersonID){
		SiteXref::createByIDs($SiteID,"Person",$PersonID,"Staff");
	}
	public static function dissociateStaff($SiteID,$PersonID){
		SiteXref::deleteByIDs($SiteID,"Person",$PersonID,"Staff");
	}
}

?>