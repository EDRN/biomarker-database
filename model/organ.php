<?php
class organ {

	public static function Create($Name) {
		$vo = new vo_organ();
		$vo->Name = $Name;
		$dao = new dao_organ();
		$dao->save(&$vo);
		$obj = new organ_object();
		$obj->ID = $vo->ID;
		$obj->Name = $vo->Name;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_organ();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchorganException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new organ_object();
				$o->ID = $r->ID;
				$o->Name = $r->Name;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->biomarkers = array();
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->biomarkers = array();
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->biomarkers = biomarker_organ::Retrieve(array("BiomarkerID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->biomarkers == null){$o->biomarkers = array();}
						} catch (NoSuchbiomarker_organException $e){
							// No results matched the query -- silently ignore
							$o->biomarkers = array();
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
		$dao = new dao_organ();
		try {
			$results = $dao->getBy(array("ID"),array("$ID"));
		} catch (NoSuchorganException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new organ_object();
		$obj->ID = $results[0]->ID;
		$obj->Name = $results[0]->Name;
		if ($lazyFetch == true){
			$obj->biomarkers = array();
		} else {
			$obj->biomarkers = biomarker_organ::Retrieve(array("BiomarkerID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->biomarkers == null){$obj->biomarkers = array();}
		}
		return $obj;
	}
	public static function Delete(&$objInstance) {
		// Delete any peers and/or children that should be removed
		foreach ($objInstance->biomarkers as $deleteme) {
			if($deleteme != array()){ biomarker_organ::Delete(&$deleteme);}
		}
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_organ();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_organ;
		$dao->save(&$vo);
	}
	public static function add_biomarker_organ($object,$biomarker_organ){
		$object->biomarkers[] = $biomarker_organ;
	}
}

class organ_object {

	public $cr_object_type = "organ";
	public $ID = '';
	public $Name = '';
	public $biomarkers = array();

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "organ";
	}

	// Accessor Functions (get)
	public function get_ID() {
		 return $this->ID;
	}
	public function get_Name() {
		 return $this->Name;
	}

	// Mutator Functions (set)
	public function set_ID($value) {
		$this->ID = $value;
	}
	public function set_Name($value) {
		$this->Name = $value;
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
		$vo = new vo_organ();
		$vo->ID = $this->ID;
		$vo->Name = $this->Name;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:organ rdf:about=\"{$urlBase}/editors/showOrgan.php?o={$this->ID}\">\r\n<{$namespace}:ID>$this->ID</{$namespace}:ID>\r\n<{$namespace}:Name>$this->Name</{$namespace}:Name>\r\n";
		foreach ($this->biomarkers as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:organ>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:organ rdf:about=\"{$urlBase}/editors/showOrgan.php?o={$this->ID}\"/>\r\n";
		return $rdf;
	}
}

class vo_organ {
	public $ID;
	public $Name;

	public function toAssocArray(){
		return array(
			"ID" => $this->ID,
			"Name" => $this->Name,
		);
	}
}

class dao_organ {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `organ` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchorganException("No organ found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_organ();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `organ` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_organ();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `organ` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_organ();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `organ`"; 
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
		$q = "DELETE FROM `organ` WHERE ID=\"$vo->ID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `organ` SET ";
		$q .= "ID=\"$vo->ID\"" . ", ";
		$q .= "Name=\"$vo->Name\" ";
		$q .= "WHERE ID=\"$vo->ID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `organ` "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->Name.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `organ`");
	}

	public function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->Name = $result['Name'];
	}

}

class NoSuchorganException extends Exception {
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