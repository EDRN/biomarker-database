<?php
class person {

	public static function Create() {
		$vo = new vo_person();
		$dao = new dao_person();
		$dao->save(&$vo);
		$obj = new person_object();
		$obj->ID = $vo->ID;
		$obj->FirstName = $vo->FirstName;
		$obj->LastName = $vo->LastName;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_person();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchpersonException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new person_object();
				$o->ID = $r->ID;
				$o->FirstName = $r->FirstName;
				$o->LastName = $r->LastName;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->site = array();
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->site = array();
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->site = person_site::Retrieve(array("PersonID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->site == null){$o->site = array();}
						} catch (NoSuchperson_siteException $e){
							// No results matched the query -- silently ignore
							$o->site = array();
						}
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
		$dao = new dao_person();
		try {
			$results = $dao->getBy(array("ID"),array("$ID"));
		} catch (NoSuchpersonException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new person_object();
		$obj->ID = $results[0]->ID;
		$obj->FirstName = $results[0]->FirstName;
		$obj->LastName = $results[0]->LastName;
		if ($lazyFetch == true){
			$obj->site = array();
		} else {
			$obj->site = person_site::Retrieve(array("PersonID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->site == null){$obj->site = array();}
		}
		return $obj;
	}
	public static function Delete(&$objInstance) {
		// Delete any peers and/or children that should be removed
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_person();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_person;
		$dao->save(&$vo);
	}
	public static function add_person_site($object,$person_site){
		$object->site[] = $person_site;
	}
}

class person_object {

	public $cr_object_type = "person";
	public $ID = '';
	public $FirstName = '';
	public $LastName = '';
	public $site = array();

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "person";
	}

	// Accessor Functions (get)
	public function get_ID() {
		 return $this->ID;
	}
	public function get_FirstName() {
		 return $this->FirstName;
	}
	public function get_LastName() {
		 return $this->LastName;
	}

	// Mutator Functions (set)
	public function set_ID($value) {
		$this->ID = $value;
	}
	public function set_FirstName($value) {
		$this->FirstName = $value;
	}
	public function set_LastName($value) {
		$this->LastName = $value;
	}


	public function equals($objArray){
		if ($objArray == null){return false;}
		foreach ($objArray as $obj){
			//echo "::EQUALS:: comparing $this->cr_object_type WITH $obj->cr_object_type &nbsp;";
			// Check object types first
			if ($this->cr_object_type == $obj->cr_object_type){
				// Check each primary key next
				if ($this->ID != $obj->ID){continue;}
				return true;
			}
		}
		return false;
	}
	public function getVO() {
		$vo = new vo_person();
		$vo->ID = $this->ID;
		$vo->FirstName = $this->FirstName;
		$vo->LastName = $this->LastName;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}
}

class vo_person {
	public $ID;
	public $FirstName;
	public $LastName;

	public function toAssocArray(){
		return array(
			"ID" => $this->ID,
			"FirstName" => $this->FirstName,
			"LastName" => $this->LastName,
		);
	}
}

class dao_person {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `person` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchpersonException("No person found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_person();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `person` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_person();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `person` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_person();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `person`"; 
		$r = $this->conn->safeGetOne($q); 
		return($r);
	}

	public function save(&$vo){
		if ($vo->ID ==0) {
			$this->insert($vo);
		} else {
			$this->update($vo);
		}
	}

	public function delete(&$vo) {
		$q = "DELETE FROM `person` WHERE ID=\"$vo->ID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `person` SET ";
		$q .= "ID=\"$vo->ID\"" . ", ";
		$q .= "FirstName=\"$vo->FirstName\"" . ", ";
		$q .= "LastName=\"$vo->LastName\" ";
		$q .= "WHERE ID=\"$vo->ID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `person` "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->FirstName.'","'.$vo->LastName.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `person`");
	}

	public function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->FirstName = $result['FirstName'];
		$vo->LastName = $result['LastName'];
	}

}

class NoSuchpersonException extends Exception {
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
?>