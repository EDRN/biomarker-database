<?php
class biomarker_organ_resource {

	public static function Create($BiomarkerID,$OrganSite,$ResourceID) {
		$vo = new vo_biomarker_organ_resource();
		$vo->BiomarkerID = $BiomarkerID;
		$vo->OrganSite = $OrganSite;
		$vo->ResourceID = $ResourceID;
		$dao = new dao_biomarker_organ_resource();
		$dao->save(&$vo);
		$obj = new biomarker_organ_resource_object();
		$obj->BiomarkerID = $vo->BiomarkerID;
		$obj->OrganSite = $vo->OrganSite;
		$obj->ResourceID = $vo->ResourceID;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_biomarker_organ_resource();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchbiomarker_organ_resourceException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new biomarker_organ_resource_object();
				$o->BiomarkerID = $r->BiomarkerID;
				$o->OrganSite = $r->OrganSite;
				$o->ResourceID = $r->ResourceID;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->resource = '';
					$o->biomarker_organ = '';
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->resource = '';
						$o->biomarker_organ = '';
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->resource = resource::Retrieve(array("ID"),array("$o->ResourceID"),$po,$lazyFetch,1);
							if ($o->resource == null){$o->resource = '';}
						} catch (NoSuchresourceException $e){
							// No results matched the query -- silently ignore
							$o->resource = '';
						}
						try{
							$o->biomarker_organ = biomarker_organ::Retrieve(array("BiomarkerID","OrganSite"),array("$o->BiomarkerID","$o->OrganSite"),$po,$lazyFetch,1);
							if ($o->biomarker_organ == null){$o->biomarker_organ = '';}
						} catch (NoSuchbiomarker_organException $e){
							// No results matched the query -- silently ignore
							$o->biomarker_organ = '';
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
	public static function Delete(&$objInstance) {
		// Delete any peers and/or children that should be removed
		if ($objInstance->resource != ''){ resource::Delete(&$objInstance->resource);}
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_biomarker_organ_resource();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_biomarker_organ_resource;
		$dao->save(&$vo);
	}
	public static function set_resource($object,$resource){
		$object->resource = $resource;
	}
}

class biomarker_organ_resource_object {

	public $cr_object_type = "biomarker_organ_resource";
	public $BiomarkerID = '';
	public $OrganSite = '';
	public $ResourceID = '';
	public $resource = '';
	public $biomarker_organ = '';

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "biomarker_organ_resource";
	}

	// Accessor Functions (get)
	public function get_BiomarkerID() {
		 return $this->BiomarkerID;
	}
	public function get_OrganSite() {
		 return $this->OrganSite;
	}
	public function get_ResourceID() {
		 return $this->ResourceID;
	}

	// Mutator Functions (set)
	public function set_BiomarkerID($value) {
		$this->BiomarkerID = $value;
	}
	public function set_OrganSite($value) {
		$this->OrganSite = $value;
	}
	public function set_ResourceID($value) {
		$this->ResourceID = $value;
	}


	public function equals($objArray){
		if ($objArray == null){return false;}
		foreach ($objArray as $obj){
			//echo "::EQUALS:: comparing $this->cr_object_type WITH $obj->cr_object_type &nbsp;";
			// Check object types first
			if ($this->cr_object_type == $obj->cr_object_type){
				// Check each primary key next
				if ($this->BiomarkerID != $obj->BiomarkerID){continue;}
				if ($this->OrganSite != $obj->OrganSite){continue;}
				if ($this->ResourceID != $obj->ResourceID){continue;}
				return true;
			}
		}
		return false;
	}
	public function getVO() {
		$vo = new vo_biomarker_organ_resource();
		$vo->BiomarkerID = $this->BiomarkerID;
		$vo->OrganSite = $this->OrganSite;
		$vo->ResourceID = $this->ResourceID;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}
}

class vo_biomarker_organ_resource {
	public $BiomarkerID;
	public $OrganSite;
	public $ResourceID;

	public function toAssocArray(){
		return array(
			"BiomarkerID" => $this->BiomarkerID,
			"OrganSite" => $this->OrganSite,
			"ResourceID" => $this->ResourceID,
		);
	}
}

class dao_biomarker_organ_resource {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("BiomarkerID","OrganSite","ResourceID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `biomarker_organ_resource` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchbiomarker_organ_resourceException("No biomarker_organ_resource found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_organ_resource();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `biomarker_organ_resource` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_organ_resource();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `biomarker_organ_resource` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_organ_resource();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `biomarker_organ_resource`"; 
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
		$q = "DELETE FROM `biomarker_organ_resource` WHERE BiomarkerID=\"$vo->BiomarkerID\" AND OrganSite=\"$vo->OrganSite\" AND ResourceID=\"$vo->ResourceID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->BiomarkerID=0;
		$vo->OrganSite=0;
		$vo->ResourceID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `biomarker_organ_resource` SET ";
		$q .= "BiomarkerID=\"$vo->BiomarkerID\"" . ", ";
		$q .= "OrganSite=\"$vo->OrganSite\"" . ", ";
		$q .= "ResourceID=\"$vo->ResourceID\" ";
		$q .= "WHERE BiomarkerID=\"$vo->BiomarkerID\" AND OrganSite=\"$vo->OrganSite\" AND ResourceID=\"$vo->ResourceID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `biomarker_organ_resource` "; 
		$q .= 'VALUES("'.$vo->BiomarkerID.'","'.$vo->OrganSite.'","'.$vo->ResourceID.'" ) ';
		$r = $this->conn->safeQuery($q);
	}

	public function getFromResult(&$vo,$result){
		$vo->BiomarkerID = $result['BiomarkerID'];
		$vo->OrganSite = $result['OrganSite'];
		$vo->ResourceID = $result['ResourceID'];
	}

}

class NoSuchbiomarker_organ_resourceException extends Exception {
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