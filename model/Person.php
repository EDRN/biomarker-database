<?php
class Person {

	public static function Create() {
		$vo = new voPerson();
		$dao = new daoPerson();
		$dao->insert(&$vo);
		$obj = new objPerson();
		$obj->applyVO($vo);
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new daoPerson();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchPersonException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new objPerson();
				$o->applyVO($r);
				//echo "-- About to check equals with parentObject->_TYPE = $parentObject->_TYPE<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){ // Be lazy, use default values
						$o->Site = array();
					} else { // Be greedy, fetch as much as possible
						$po = $parentObjects;
						$po[] = &$o;
						$o->Site = PersonXref::retrieve($o,"Site",$po,$lazyFetch,$limit,"Site");
						if ($o->Site == null){$o->Site = array();}
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
		$dao = new daoPerson();
		try {
			$results = $dao->getBy(array("ID"),array("{$ID}"));
		} catch (NoSuchPersonException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new objPerson;
		$obj->applyVO($results[0]);
		if ($lazyFetch == true){ // Be lazy, use default values
			$obj->Site = array();
		} else { // Be greedy, fetch as much as possible
			$limit = 0; // get all children
			$obj->Site = PersonXref::retrieve($obj,"Site",array($obj),$lazyFetch,$limit,"Site");
			if ($obj->Site == null){$obj->Site = array();}
		}
		return $obj;
	}
	public static function MultiDelete($objectIDs,&$db = null) {
		//Delete children first
		if ($db == null){$db = new cwsp_db(Modeler::DSN);}
		//Detach these objects from all other objects
		$db->safeQuery("DELETE FROM `xr_Person_Site` WHERE PersonID IN (".implode(",",$objectIDs).")");
		//Finally, delete the objects themselves
		$db->safeQuery("DELETE FROM `Person` WHERE ID IN (".implode(",",$objectIDs).")");
	}
	public static function Delete($objID) {
		$db = new cwsp_db(Modeler::DSN);
		//Delete children first
		//Detach this object from all other objects
		$db->safeQuery("DELETE FROM `xr_Person_Site` WHERE PersonID = $objID");
		//Finally, delete the object itself
		$db->safeQuery("DELETE FROM `Person` WHERE ID = $objID");
	}
	public static function Exists() {
		$dao = new daoPerson();
		try {
			$dao->getBy(array(),array());
		} catch (NoSuchPersonException $e){
			return false;
		}
		return true;
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new daoPerson;
		$dao->save(&$vo);
	}
	public static function attach_Site($object,$Site){
		$object->Site[] = $Site;
	}
}

class PersonVars {
	const PER_OBJID = "objId";
	const PER_FIRSTNAME = "FirstName";
	const PER_LASTNAME = "LastName";
	const PER_TITLE = "Title";
	const PER_SPECIALTY = "Specialty";
	const PER_PHONE = "Phone";
	const PER_FAX = "Fax";
	const PER_EMAIL = "Email";
	const PER_SITE = "Site";
}

class objPerson {

	const _TYPE = "Person";
	private $XPress;
	public $objId = '';
	public $FirstName = '';
	public $LastName = '';
	public $Title = '';
	public $Specialty = '';
	public $Phone = '';
	public $Fax = '';
	public $Email = '';
	public $Site = array();


	public function __construct(&$XPressObj,$objId = 0) {
		//echo "creating object of type Person<br/>";
		$this->XPress = $XPressObj;
		$this->objId = $objId;
	}
	public function initialize($objId,$inflate = true,$parentObjects = array()){
		$this->objId = $objId;
		$q = "SELECT * FROM `Person` WHERE objId={$this->objId} LIMIT 1";
		$r = $this->XPress->Database->safeQuery($q);
		if ($r->numRows() != 1){
			return false;
		} else {
			$result = $r->fetchRow(DB_FETCHMODE_ASSOC);
			$this->objId = $result['objId'];
			$this->FirstName = $result['FirstName'];
			$this->LastName = $result['LastName'];
			$this->Title = $result['Title'];
			$this->Specialty = $result['Specialty'];
			$this->Phone = $result['Phone'];
			$this->Fax = $result['Fax'];
			$this->Email = $result['Email'];
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
		$this->FirstName = '';
		$this->LastName = '';
		$this->Title = '';
		$this->Specialty = '';
		$this->Phone = '';
		$this->Fax = '';
		$this->Email = '';
		$this->Site = array();
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
		 return $this->Site;
	}

	// Mutator Functions 
	public function setObjId($value,$bSave = true) {
		$this->objId = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setFirstName($value,$bSave = true) {
		$this->FirstName = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setLastName($value,$bSave = true) {
		$this->LastName = $value;
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
	public function setSpecialty($value,$bSave = true) {
		$this->Specialty = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setPhone($value,$bSave = true) {
		$this->Phone = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setFax($value,$bSave = true) {
		$this->Fax = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function setEmail($value,$bSave = true) {
		$this->Email = $value;
		if ($bSave){
			$this->save();
		}
	}
	public function create(){
		$this->save();
	}
	public function inflate($parentObjects) {
		if ($this->equals($parentObjects)) {
			return false;
		}
		$parentObjects[] = $this;
		// Inflate "Site":
		$q = "SELECT SiteID AS objId FROM xr_Person_Site WHERE PersonID = {$this->objId} AND PersonVar = \"Site\" ";
		$r  = $this->XPress->Database->safeQuery($q);
		$rcount = 0;
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			if ($limit > 0 && $rcount > $limit){break;}
			// Retrieve the objects one by one... (limit = 1 per request) 
			$obj = new objSite($this->XPress);
			if ($obj->initialize($result['objId'],true,$parentObjects) ){
				$this->Site[] = $obj;
			}
			$rcount++;
		}
		return true;
	}
	public function save() {
		if ($this->objId == 0){
			// Insert a new object into the db
			$q = "INSERT INTO `Person` ";
			$q .= 'VALUES("'.$this->objId.'","'.$this->FirstName.'","'.$this->LastName.'","'.$this->Title.'","'.$this->Specialty.'","'.$this->Phone.'","'.$this->Fax.'","'.$this->Email.'") ';
			$r = $this->XPress->Database->safeQuery($q);
			$this->objId = $this->XPress->Database->safeGetOne("SELECT LAST_INSERT_ID() FROM `Person`");
		} else {
			// Update an existing object in the db
			$q = "UPDATE `Person` SET ";
			$q .= "`objId`=\"$this->objId\","; 
			$q .= "`FirstName`=\"$this->FirstName\","; 
			$q .= "`LastName`=\"$this->LastName\","; 
			$q .= "`Title`=\"$this->Title\","; 
			$q .= "`Specialty`=\"$this->Specialty\","; 
			$q .= "`Phone`=\"$this->Phone\","; 
			$q .= "`Fax`=\"$this->Fax\","; 
			$q .= "`Email`=\"$this->Email\" ";
			$q .= "WHERE `objId` = $this->objId ";
			$r = $this->XPress->Database->safeQuery($q);
		}
	}
	public function delete(){
		//Intelligently unlink this object from any other objects
		$this->unlink(PersonVars::PER_SITE);
		//Delete object from the database
		$q = "DELETE FROM `Person` WHERE `objId` = $this->objId ";
		$r = $this->XPress->Database->safeQuery($q);
		$this->deflate();
	}
	public function _getType(){
		return "Person";
	}
	public function link($variable,$remoteID,$remoteVar=''){
		switch ($variable){
			case "Site":
				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE PersonID=$this->objId AND SiteID=$remoteID ";
				$q0 = "INSERT INTO xr_Person_Site (PersonID,SiteID,PersonVar".(($remoteVar == '')? '' : ',SiteVar').") VALUES($this->objId,$remoteID,\"Site\"".(($remoteVar == '')? '' : ",\"{$remoteVar}\"").");";
				$q1 = "UPDATE xr_Person_Site SET PersonVar=\"{$variable}\" ".(($remoteVar == '')? '' : ', SiteVar="{$remoteVar}" ')." WHERE PersonID=$this->objId AND SiteID=$remoteID LIMIT 1 ";
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
			case "Site":
				$q = "DELETE FROM xr_Person_Site WHERE PersonID = $this->objId ".((empty($remoteIDs)) ? '' : (" AND SiteID2 ". ((is_array($remoteIDs))? " IN (".implode(',',$remoteIDs).") . " : " = $remoteIDs "))) ." AND PersonVar = \"Site\" ";
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
	public function associate($objectID,$variableName) {
		switch ($variableName) {
			case "Site":
				PersonXref::createByIDs($this->ID,"Site",$objectID,"Site");
				break;
			default: 
				return false;
		}
		return true;
	}
	public function dissociate($objectID,$variableName) {
		switch ($variableName) {
			case "Site":
				PersonXref::deleteByIDs($this->ID,"Site",$objectID,"Site");
				break;
			default:
				return false;
		}
	}
	public function getVO() {
		$vo = new voPerson();
		$vo->objId = $this->objId;
		$vo->FirstName = $this->FirstName;
		$vo->LastName = $this->LastName;
		$vo->Title = $this->Title;
		$vo->Specialty = $this->Specialty;
		$vo->Phone = $this->Phone;
		$vo->Fax = $this->Fax;
		$vo->Email = $this->Email;
		return $vo;
	}
	public function applyVO($voPerson) {
		if(!empty($voPerson->objId)){
			$this->objId = $voPerson->objId;
		}
		if(!empty($voPerson->FirstName)){
			$this->FirstName = $voPerson->FirstName;
		}
		if(!empty($voPerson->LastName)){
			$this->LastName = $voPerson->LastName;
		}
		if(!empty($voPerson->Title)){
			$this->Title = $voPerson->Title;
		}
		if(!empty($voPerson->Specialty)){
			$this->Specialty = $voPerson->Specialty;
		}
		if(!empty($voPerson->Phone)){
			$this->Phone = $voPerson->Phone;
		}
		if(!empty($voPerson->Fax)){
			$this->Fax = $voPerson->Fax;
		}
		if(!empty($voPerson->Email)){
			$this->Email = $voPerson->Email;
		}
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}
}

class voPerson {
	public $objId;
	public $FirstName;
	public $LastName;
	public $Title;
	public $Specialty;
	public $Phone;
	public $Fax;
	public $Email;

	public function toAssocArray(){
		return array(
			"objId" => $this->objId,
			"FirstName" => $this->FirstName,
			"LastName" => $this->LastName,
			"Title" => $this->Title,
			"Specialty" => $this->Specialty,
			"Phone" => $this->Phone,
			"Fax" => $this->Fax,
			"Email" => $this->Email,
		);
	}
	public function applyAssocArray($arr) {
		if(!empty($arr['objId'])){
			$this->objId = $arr['objId'];
		}
		if(!empty($arr['FirstName'])){
			$this->FirstName = $arr['FirstName'];
		}
		if(!empty($arr['LastName'])){
			$this->LastName = $arr['LastName'];
		}
		if(!empty($arr['Title'])){
			$this->Title = $arr['Title'];
		}
		if(!empty($arr['Specialty'])){
			$this->Specialty = $arr['Specialty'];
		}
		if(!empty($arr['Phone'])){
			$this->Phone = $arr['Phone'];
		}
		if(!empty($arr['Fax'])){
			$this->Fax = $arr['Fax'];
		}
		if(!empty($arr['Email'])){
			$this->Email = $arr['Email'];
		}
	}
}

class daoPerson {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `Person` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchPersonException("No Person found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voPerson();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Person` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voPerson();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `Person` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new voPerson();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Person`"; 
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
		$q = "DELETE FROM `Person` WHERE `ID` = $vo->ID ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID = 0; // reset the id of the passed value object
	}

	public function update(&$vo){
		$q = "UPDATE `Person` SET ";
		$q .= "objId=\"$vo->objId\"" . ", ";
		$q .= "FirstName=\"$vo->FirstName\"" . ", ";
		$q .= "LastName=\"$vo->LastName\"" . ", ";
		$q .= "Title=\"$vo->Title\"" . ", ";
		$q .= "Specialty=\"$vo->Specialty\"" . ", ";
		$q .= "Phone=\"$vo->Phone\"" . ", ";
		$q .= "Fax=\"$vo->Fax\"" . ", ";
		$q .= "Email=\"$vo->Email\" ";
		$q .= "WHERE `ID` = $vo->ID ";
		$r = $this->conn->safeQuery($q);
	}

	public function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Person` "; 
		$q .= 'VALUES("'.$vo->objId.'","'.$vo->FirstName.'","'.$vo->LastName.'","'.$vo->Title.'","'.$vo->Specialty.'","'.$vo->Phone.'","'.$vo->Fax.'","'.$vo->Email.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Person`");
	}

	public function getFromResult(&$vo,$result){
		$vo->objId = $result['objId'];
		$vo->FirstName = $result['FirstName'];
		$vo->LastName = $result['LastName'];
		$vo->Title = $result['Title'];
		$vo->Specialty = $result['Specialty'];
		$vo->Phone = $result['Phone'];
		$vo->Fax = $result['Fax'];
		$vo->Email = $result['Email'];
	}

}

class NoSuchPersonException extends Exception {
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

class PersonXref {
	public static function createByIDs($localID,$remoteType,$remoteID,$variableName) {
		$q = $q0 = $q1 = "";
		switch($variableName) {
			case "Site":
				$q  = "SELECT COUNT(*) FROM xr_Person_Site WHERE PersonID=$localID AND SiteID=$remoteID LIMIT 1 ";
				$q0 = "INSERT INTO xr_Person_Site (PersonID,SiteID,PersonVar) VALUES($localID,$remoteID,\"Site\");";
				$q1 = "UPDATE xr_Person_Site SET PersonVar=\"{$variableName}\" WHERE PersonID=$localID AND SiteID=$remoteID LIMIT 1 ";
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
			case "Site":
				$q = "DELETE FROM xr_Person_Site WHERE PersonID = $localID AND SiteID = $remoteID AND PersonVar = \"Site\" LIMIT 1";
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
			case "Site":
				$q = "SELECT SiteID AS ID FROM xr_Person_Site WHERE PersonID = {$local->ID} AND PersonVar = \"Site\" ";
				$db = new cwsp_db(Modeler::DSN);
				$r  = $db->safeQuery($q);
				$rcount = 0;
				while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
					if ($limit > 0 && $rcount > $limit){break;}
					// Retrieve the objects one by one... (limit = 1 per request) 
					$objects[] = Site::Retrieve(array("ID"),array("{$result['ID']}"),$parentObjects,$lazyFetch,1);
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
			case "Site":
				$q = "DELETE FROM `xr_Person_Site` WHERE PersonID = $objectID AND PersonVar = \"Site\" ";
				break;
			default:
				break;
		}
		$db = new cwsp_db(Modeler::DSN);
		$r  = $db->safeQuery($q);
		return true;
	}
}

class PersonActions {
	public static function associateSite($PersonID,$SiteID){
		PersonXref::createByIDs($PersonID,"Site",$SiteID,"Site");
	}
	public static function dissociateSite($PersonID,$SiteID){
		PersonXref::deleteByIDs($PersonID,"Site",$SiteID,"Site");
	}
}

?>