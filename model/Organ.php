<?php
class Organ {

	public static function Create($Name) {
		$vo = new voOrgan();
		$vo->Name = $Name;
		$dao = new daoOrgan();
		$dao->insert(&$vo);
		$obj = new objOrgan();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoOrgan();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchOrganException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objOrgan();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->OrganDatas = array();
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->OrganDatas = OrganXref::retrieve($o,"BiomarkerOrganData",$po,$lazyFetch,$limit,"OrganDatas");
						if ($o->OrganDatas == null){$o->OrganDatas = array();}
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
		$dao = new daoOrgan();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchOrganException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objOrgan;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->OrganDatas = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->OrganDatas = OrganXref::retrieve($obj,"BiomarkerOrganData",array($obj),$lazyFetch,$limit,"OrganDatas");
			if ($obj->OrganDatas == null){$obj->OrganDatas = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		$rows = $db->safeGetAll("SELECT BiomarkerOrganDataID FROM `xr_BiomarkerOrganData_Organ` WHERE OrganID IN (".implode(",",$objectIDs).")");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerOrganDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerOrganData::MultiDelete($goners,$db);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Organ` WHERE OrganID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `Organ` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		$rows = $db->safeGetAll("SELECT BiomarkerOrganDataID FROM `xr_BiomarkerOrganData_Organ` WHERE OrganID = $objID ");
		foreach ($rows as $data){
			$goners[] = $data['BiomarkerOrganDataID'];
		}
		if (sizeof($goners) > 0){BiomarkerOrganData::MultiDelete($goners,$db);}
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_BiomarkerOrganData_Organ` WHERE OrganID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `Organ` WHERE ID = $objID");
	}
	public static function Exists($Name) {
		$dao = new daoOrgan();
		try {
			$dao->getBy(array("Name"),array($Name));
		} catch (NoSuchOrganException $e){
			return false;
		}
		return true;
	}
	public static function RetrieveUnique( $Name,$lazyFetch=false) {
		$dao = new daoOrgan();
		try {
			$results = $dao->getBy(array("Name"),array($Name));
			return Organ::RetrieveByID($results[0]->ID,$lazyFetch);
		} catch (NoSuchOrganException $e){
			return false;
		}
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoOrgan;
		$dao->save(&$vo);
	}
	public static function attach_OrganData($object,$BiomarkerOrganData){
		$object->OrganDatas[] = $BiomarkerOrganData;
	}
}

class OrganVars {
	const ORG_OBJID = "objId";
	const ORG_NAME = "Name";
	const ORG_ORGANDATAS = "OrganDatas";
}

class objOrgan {

	const _TYPE = "Organ";
	private $XPress;
	public $objId = '';
	public $Name = '';
	public $OrganDatas = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Organ<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		$this->objId = $objId;
		$q = "SELECT * FROM `Organ` WHERE objId={$this->objId} LIMIT 1";
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
		$this->OrganDatas = array();
	}

	// Accessor Functions
	public function getObjId() {
		 return $this->objId;
	}
	public function getName() {
		 return $this->Name;
	}
	public function getOrganDatas() {
		 return $this->OrganDatas;
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
	public function create($Name){
		$this->Name = $Name;
		$this->save();
	}
	public function inflate($parentObjects = array()) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "OrganDatas":
		$q = "SELECT BiomarkerOrganDataID AS objId FROM xr_BiomarkerOrganData_Organ WHERE OrganID = {$this->objId} AND OrganVar = \"OrganDatas\" ";
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
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Organ` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->Name.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Organ`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Organ` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`Name`=\"$this->Name\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(OrganVars::ORG_ORGANDATAS);
		//Delete this object's child objects
		foreach ($this->OrganDatas as $obj){
			$obj->delete();
		}

		//Delete object from the database
		$q = "DELETE FROM `Organ` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Organ";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "OrganDatas":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Organ WHERE OrganID=$this->objId AND BiomarkerOrganDataID=$remoteID ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Organ (OrganID,BiomarkerOrganDataID,OrganVar".(($remoteVar == '')? '' : ',BiomarkerOrganDataVar').") VALUES($this->objId,$remoteID,\"OrganDatas\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Organ SET OrganVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', BiomarkerOrganDataVar="{$remoteVar}" ')." WHERE OrganID=$this->objId AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
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
			case "OrganDatas":
				$q = "DELETE FROM xr_BiomarkerOrganData_Organ WHERE OrganID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND BiomarkerOrganDataID ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND OrganVar = \"OrganDatas\" ";
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
			case "OrganDatas":
				OrganXref::deleteByIDs($this->ID,"BiomarkerOrganData",$objectID,"OrganDatas");
				BiomarkerOrganData::Delete($objectID);
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voOrgan();
		$vo->objId = $this->objId;
		$vo->Name = $this->Name;
		return $vo;
	}
	public function applyVO($voOrgan) {
		if(!empty($voOrgan->objId)){
			$this->objId = $voOrgan->objId;
		}
		if(!empty($voOrgan->Name)){
			$this->Name = $voOrgan->Name;
		}
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Organ rdf:about=\"{$urlBase}/editors/showOrgan.php?o={$this->ID}\">\r\n<{$namespace}:objId>$this->objId</{$namespace}:objId>\r\n<{$namespace}:Name>$this->Name</{$namespace}:Name>\r\n";
		foreach ($this->OrganDatas as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:Organ>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:Organ rdf:about=\"{$urlBase}/editors/showOrgan.php?o={$this->ID}\"/>\r\n";
		return $rdf;
	}
}

class voOrgan {
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

class daoOrgan {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `Organ` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchOrganException("No Organ found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voOrgan();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Organ` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voOrgan();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `Organ` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voOrgan();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Organ`"; 
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
		$q = "DELETE FROM `Organ` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `Organ` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "Name=\"$vo->Name\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Organ` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->Name.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Organ`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->Name = $result['Name'];
	}

}

class NoSuchOrganException extends Exception {
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

class OrganXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "OrganDatas":
				$q  = "SELECT COUNT(*) FROM xr_BiomarkerOrganData_Organ WHERE OrganID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_BiomarkerOrganData_Organ (OrganID,BiomarkerOrganDataID,OrganVar) VALUES($localID,$remoteID,\"OrganDatas\");";
				$q1 = "UPDATE xr_BiomarkerOrganData_Organ SET OrganVar=\"{$variableName}\" WHERE OrganID=$localID AND BiomarkerOrganDataID=$remoteID LIMIT 1 ";
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
			case "OrganDatas":
				$q = "DELETE FROM xr_BiomarkerOrganData_Organ WHERE OrganID = $localID AND BiomarkerOrganDataID = $remoteID AND OrganVar = \"OrganDatas\" LIMIT 1";
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
			case "OrganDatas":
				$q = "SELECT BiomarkerOrganDataID AS ID FROM xr_BiomarkerOrganData_Organ WHERE OrganID = {$local->ID} AND OrganVar = \"OrganDatas\" ";
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
			case "OrganDatas":
				$q = "DELETE FROM `xr_BiomarkerOrganData_Organ` WHERE OrganID = $objectID AND OrganVar = \"OrganDatas\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class OrganActions {
	public static function associateOrganData($OrganID,$BiomarkerOrganDataID){
		OrganXref::createByIDs($OrganID,"BiomarkerOrganData",$BiomarkerOrganDataID,"OrganDatas");
	}
	public static function dissociateOrganData($OrganID,$BiomarkerOrganDataID){
		BiomarkerOrganData::Delete($BiomarkerOrganDataID);
	}
}

?>