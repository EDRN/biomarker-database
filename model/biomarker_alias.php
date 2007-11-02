<?php
class biomarker_alias {

	public static function Create($BiomarkerID,$Alias) {
		$vo = new vo_biomarker_alias();
		$vo->BiomarkerID = $BiomarkerID;
		$vo->Alias = $Alias;
		$dao = new dao_biomarker_alias();
		$dao->save(&$vo);
		$obj = new biomarker_alias_object();
		$obj->BiomarkerID = $vo->BiomarkerID;
		$obj->Alias = $vo->Alias;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_biomarker_alias();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchbiomarker_aliasException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new biomarker_alias_object();
				$o->BiomarkerID = $r->BiomarkerID;
				$o->Alias = $r->Alias;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
					} else {
						$po = $parentObjects;
						$po[] = &$o;
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
	public static function Delete(&$objInstance) {
		// Delete any peers and/or children that should be removed
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_biomarker_alias();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_biomarker_alias;
		$dao->save(&$vo);
	}
}

class biomarker_alias_object {

	public $cr_object_type = "biomarker_alias";
	public $BiomarkerID = '';
	public $Alias = '';

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "biomarker_alias";
	}

	// Accessor Functions (get)
	public function get_BiomarkerID() {
		 return $this->BiomarkerID;
	}
	public function get_Alias() {
		 return $this->Alias;
	}

	// Mutator Functions (set)
	public function set_BiomarkerID($value) {
		$this->BiomarkerID = $value;
	}
	public function set_Alias($value) {
		$this->Alias = $value;
	}


	public function equals($objArray){
		if ($objArray == null){return false;}
		foreach ($objArray as $obj){
			//echo "::EQUALS:: comparing $this->cr_object_type WITH $obj->cr_object_type &nbsp;";
			// Check object types first
			if ($this->cr_object_type == $obj->cr_object_type){
				// Check each primary key next
				if ($this->BiomarkerID != $obj->BiomarkerID){continue;}
				if ($this->Alias != $obj->Alias){continue;}
				return true;
			}
		}
		return false;
	}
	public function getVO() {
		$vo = new vo_biomarker_alias();
		$vo->BiomarkerID = $this->BiomarkerID;
		$vo->Alias = $this->Alias;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}
}

class vo_biomarker_alias {
	public $BiomarkerID;
	public $Alias;

	public function toAssocArray(){
		return array(
			"BiomarkerID" => $this->BiomarkerID,
			"Alias" => $this->Alias,
		);
	}
}

class dao_biomarker_alias {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("BiomarkerID","Alias");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `biomarker_alias` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchbiomarker_aliasException("No biomarker_alias found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_alias();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `biomarker_alias` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_alias();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `biomarker_alias` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_alias();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `biomarker_alias`"; 
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
		$q = "DELETE FROM `biomarker_alias` WHERE BiomarkerID=\"$vo->BiomarkerID\" AND Alias=\"$vo->Alias\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->BiomarkerID=0;
		$vo->Alias=0;
	}

	public function update(&$vo){
		$q = "UPDATE `biomarker_alias` SET ";
		$q .= "BiomarkerID=\"$vo->BiomarkerID\"" . ", ";
		$q .= "Alias=\"$vo->Alias\" ";
		$q .= "WHERE BiomarkerID=\"$vo->BiomarkerID\" AND Alias=\"$vo->Alias\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `biomarker_alias` "; 
		$q .= 'VALUES("'.$vo->BiomarkerID.'","'.$vo->Alias.'" ) ';
		$r = $this->conn->safeQuery($q);
	}

	public function getFromResult(&$vo,$result){
		$vo->BiomarkerID = $result['BiomarkerID'];
		$vo->Alias = $result['Alias'];
	}

}

class NoSuchbiomarker_aliasException extends Exception {
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