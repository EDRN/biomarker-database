<?php
class resource {

	public static function Create($Name,$URL) {
		$vo = new vo_resource();
		$vo->Name = $Name;
		$vo->URL = $URL;
		$dao = new dao_resource();
		$dao->save(&$vo);
		$obj = new resource_object();
		$obj->ID = $vo->ID;
		$obj->Name = $vo->Name;
		$obj->URL = $vo->URL;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_resource();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchresourceException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new resource_object();
				$o->ID = $r->ID;
				$o->Name = $r->Name;
				$o->URL = $r->URL;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->biomarkers = array();
					$o->biomarker_organs = array();
					$o->studies = array();
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->biomarkers = array();
						$o->biomarker_organs = array();
						$o->studies = array();
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->biomarkers = biomarker_resource::Retrieve(array("ResourceID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->biomarkers == null){$o->biomarkers = array();}
						} catch (NoSuchbiomarker_resourceException $e){
							// No results matched the query -- silently ignore
							$o->biomarkers = array();
						}
						try{
							$o->biomarker_organs = biomarker_organ_resource::Retrieve(array("ResourceID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->biomarker_organs == null){$o->biomarker_organs = array();}
						} catch (NoSuchbiomarker_organ_resourceException $e){
							// No results matched the query -- silently ignore
							$o->biomarker_organs = array();
						}
						try{
							$o->studies = study_resource::Retrieve(array("ResourceID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->studies == null){$o->studies = array();}
						} catch (NoSuchstudy_resourceException $e){
							// No results matched the query -- silently ignore
							$o->studies = array();
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
		$dao = new dao_resource();
		try {
			$results = $dao->getBy(array("ID"),array("$ID"));
		} catch (NoSuchresourceException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new resource_object();
		$obj->ID = $results[0]->ID;
		$obj->Name = $results[0]->Name;
		$obj->URL = $results[0]->URL;
		if ($lazyFetch == true){
			$obj->biomarkers = array();
			$obj->biomarker_organs = array();
			$obj->studies = array();
		} else {
			$obj->biomarkers = biomarker_resource::Retrieve(array("ResourceID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->biomarkers == null){$obj->biomarkers = array();}
			$obj->biomarker_organs = biomarker_organ_resource::Retrieve(array("ResourceID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->biomarker_organs == null){$obj->biomarker_organs = array();}
			$obj->studies = study_resource::Retrieve(array("ResourceID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->studies == null){$obj->studies = array();}
		}
		return $obj;
	}
	public static function Delete(&$objInstance) {
		// Delete any peers and/or children that should be removed
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_resource();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_resource;
		$dao->save(&$vo);
	}
	public static function add_biomarker_resource($object,$biomarker_resource){
		$object->biomarkers[] = $biomarker_resource;
	}
	public static function add_biomarker_organ_resource($object,$biomarker_organ_resource){
		$object->biomarker_organs[] = $biomarker_organ_resource;
	}
	public static function add_study_resource($object,$study_resource){
		$object->studies[] = $study_resource;
	}
}

class resource_object {

	public $cr_object_type = "resource";
	public $ID = '';
	public $Name = '';
	public $URL = '';
	public $biomarkers = array();
	public $biomarker_organs = array();
	public $studies = array();

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "resource";
	}

	// Accessor Functions (get)
	public function get_ID() {
		 return $this->ID;
	}
	public function get_Name() {
		 return $this->Name;
	}
	public function get_URL() {
		 return $this->URL;
	}

	// Mutator Functions (set)
	public function set_ID($value) {
		$this->ID = $value;
	}
	public function set_Name($value) {
		$this->Name = $value;
	}
	public function set_URL($value) {
		$this->URL = $value;
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
		$vo = new vo_resource();
		$vo->ID = $this->ID;
		$vo->Name = $this->Name;
		$vo->URL = $this->URL;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}
}

class vo_resource {
	public $ID;
	public $Name;
	public $URL;

	public function toAssocArray(){
		return array(
			"ID" => $this->ID,
			"Name" => $this->Name,
			"URL" => $this->URL,
		);
	}
}

class dao_resource {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `resource` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchresourceException("No resource found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_resource();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `resource` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_resource();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `resource` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_resource();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `resource`"; 
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
		$q = "DELETE FROM `resource` WHERE ID=\"$vo->ID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `resource` SET ";
		$q .= "ID=\"$vo->ID\"" . ", ";
		$q .= "Name=\"$vo->Name\"" . ", ";
		$q .= "URL=\"$vo->URL\" ";
		$q .= "WHERE ID=\"$vo->ID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `resource` "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->Name.'","'.$vo->URL.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `resource`");
	}

	public function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->Name = $result['Name'];
		$vo->URL = $result['URL'];
	}

}

class NoSuchresourceException extends Exception {
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