<?php
class study {

	public static function Create($Title) {
		$vo = new vo_study();
		$vo->Title = $Title;
		$dao = new dao_study();
		$dao->save(&$vo);
		$obj = new study_object();
		$obj->ID = $vo->ID;
		$obj->EDRNID = $vo->EDRNID;
		$obj->FHCRC_ID = $vo->FHCRC_ID;
		$obj->DMCC_ID = $vo->DMCC_ID;
		$obj->Title = $vo->Title;
		$obj->Abstract = $vo->Abstract;
		$obj->BiomarkerPopulationCharacteristics = $vo->BiomarkerPopulationCharacteristics;
		$obj->Design = $vo->Design;
		$obj->BiomarkerStudyType = $vo->BiomarkerStudyType;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_study();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchstudyException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new study_object();
				$o->ID = $r->ID;
				$o->EDRNID = $r->EDRNID;
				$o->FHCRC_ID = $r->FHCRC_ID;
				$o->DMCC_ID = $r->DMCC_ID;
				$o->Title = $r->Title;
				$o->Abstract = $r->Abstract;
				$o->BiomarkerPopulationCharacteristics = $r->BiomarkerPopulationCharacteristics;
				$o->Design = $r->Design;
				$o->BiomarkerStudyType = $r->BiomarkerStudyType;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->biomarkers = array();
					$o->publications = array();
					$o->resources = array();
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->biomarkers = array();
						$o->publications = array();
						$o->resources = array();
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->biomarkers = biomarker_study::Retrieve(array("StudyID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->biomarkers == null){$o->biomarkers = array();}
						} catch (NoSuchbiomarker_studyException $e){
							// No results matched the query -- silently ignore
							$o->biomarkers = array();
						}
						try{
							$o->publications = study_publication::Retrieve(array("StudyID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->publications == null){$o->publications = array();}
						} catch (NoSuchstudy_publicationException $e){
							// No results matched the query -- silently ignore
							$o->publications = array();
						}
						try{
							$o->resources = study_resource::Retrieve(array("StudyID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->resources == null){$o->resources = array();}
						} catch (NoSuchstudy_resourceException $e){
							// No results matched the query -- silently ignore
							$o->resources = array();
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
		$dao = new dao_study();
		try {
			$results = $dao->getBy(array("ID"),array("$ID"));
		} catch (NoSuchstudyException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new study_object();
		$obj->ID = $results[0]->ID;
		$obj->EDRNID = $results[0]->EDRNID;
		$obj->FHCRC_ID = $results[0]->FHCRC_ID;
		$obj->DMCC_ID = $results[0]->DMCC_ID;
		$obj->Title = $results[0]->Title;
		$obj->Abstract = $results[0]->Abstract;
		$obj->BiomarkerPopulationCharacteristics = $results[0]->BiomarkerPopulationCharacteristics;
		$obj->Design = $results[0]->Design;
		$obj->BiomarkerStudyType = $results[0]->BiomarkerStudyType;
		if ($lazyFetch == true){
			$obj->biomarkers = array();
			$obj->publications = array();
			$obj->resources = array();
		} else {
			$obj->biomarkers = biomarker_study::Retrieve(array("StudyID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->biomarkers == null){$obj->biomarkers = array();}
			$obj->publications = study_publication::Retrieve(array("StudyID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->publications == null){$obj->publications = array();}
			$obj->resources = study_resource::Retrieve(array("StudyID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->resources == null){$obj->resources = array();}
		}
		return $obj;
	}
	public static function Delete(&$objInstance) {
		// Delete any peers and/or children that should be removed
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_study();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_study;
		$dao->save(&$vo);
	}
	public static function add_biomarker_study($object,$biomarker_study){
		$object->biomarkers[] = $biomarker_study;
	}
	public static function add_study_publication($object,$study_publication){
		$object->publications[] = $study_publication;
	}
	public static function add_study_resource($object,$study_resource){
		$object->resources[] = $study_resource;
	}
}

class study_object {

	public $cr_object_type = "study";
	public $ID = '';
	public $EDRNID = '';
	public $FHCRC_ID = '';
	public $DMCC_ID = '';
	public $Title = '';
	public $Abstract = '';
	public $BiomarkerPopulationCharacteristics = '';
	public $Design = '';
	public $BiomarkerStudyType = '';
	public $biomarkers = array();
	public $publications = array();
	public $resources = array();

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "study";
	}

	// Accessor Functions (get)
	public function get_ID() {
		 return $this->ID;
	}
	public function get_EDRNID() {
		 return $this->EDRNID;
	}
	public function get_FHCRC_ID() {
		 return $this->FHCRC_ID;
	}
	public function get_DMCC_ID() {
		 return $this->DMCC_ID;
	}
	public function get_Title() {
		 return $this->Title;
	}
	public function get_Abstract() {
		 return $this->Abstract;
	}
	public function get_BiomarkerPopulationCharacteristics() {
		 return $this->BiomarkerPopulationCharacteristics;
	}
	public function get_Design() {
		 return $this->Design;
	}
	public function get_BiomarkerStudyType() {
		 return $this->BiomarkerStudyType;
	}

	// Mutator Functions (set)
	public function set_ID($value) {
		$this->ID = $value;
	}
	public function set_EDRNID($value) {
		$this->EDRNID = $value;
	}
	public function set_FHCRC_ID($value) {
		$this->FHCRC_ID = $value;
	}
	public function set_DMCC_ID($value) {
		$this->DMCC_ID = $value;
	}
	public function set_Title($value) {
		$this->Title = $value;
	}
	public function set_Abstract($value) {
		$this->Abstract = $value;
	}
	public function set_BiomarkerPopulationCharacteristics($value) {
		$this->BiomarkerPopulationCharacteristics = $value;
	}
	public function set_Design($value) {
		$this->Design = $value;
	}
	public function set_BiomarkerStudyType($value) {
		$this->BiomarkerStudyType = $value;
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
		$vo = new vo_study();
		$vo->ID = $this->ID;
		$vo->EDRNID = $this->EDRNID;
		$vo->FHCRC_ID = $this->FHCRC_ID;
		$vo->DMCC_ID = $this->DMCC_ID;
		$vo->Title = $this->Title;
		$vo->Abstract = $this->Abstract;
		$vo->BiomarkerPopulationCharacteristics = $this->BiomarkerPopulationCharacteristics;
		$vo->Design = $this->Design;
		$vo->BiomarkerStudyType = $this->BiomarkerStudyType;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:study rdf:about=\"{$urlBase}/editors/showStudy.php?s={$this->ID}\">\r\n<{$namespace}:ID>$this->ID</{$namespace}:ID>\r\n<{$namespace}:EDRNID>$this->EDRNID</{$namespace}:EDRNID>\r\n<{$namespace}:FHCRC_ID>$this->FHCRC_ID</{$namespace}:FHCRC_ID>\r\n<{$namespace}:DMCC_ID>$this->DMCC_ID</{$namespace}:DMCC_ID>\r\n<{$namespace}:Title>$this->Title</{$namespace}:Title>\r\n<{$namespace}:Abstract>$this->Abstract</{$namespace}:Abstract>\r\n<{$namespace}:BiomarkerPopulationCharacteristics>$this->BiomarkerPopulationCharacteristics</{$namespace}:BiomarkerPopulationCharacteristics>\r\n<{$namespace}:Design>$this->Design</{$namespace}:Design>\r\n<{$namespace}:BiomarkerStudyType>$this->BiomarkerStudyType</{$namespace}:BiomarkerStudyType>\r\n";
		foreach ($this->biomarkers as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->publications as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->resources as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:study>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:study rdf:about=\"{$urlBase}/editors/showStudy.php?s={$this->ID}\"/>\r\n";
		return $rdf;
	}
}

class vo_study {
	public $ID;
	public $EDRNID;
	public $FHCRC_ID;
	public $DMCC_ID;
	public $Title;
	public $Abstract;
	public $BiomarkerPopulationCharacteristics;
	public $Design;
	public $BiomarkerStudyType;

	public function toAssocArray(){
		return array(
			"ID" => $this->ID,
			"EDRNID" => $this->EDRNID,
			"FHCRC_ID" => $this->FHCRC_ID,
			"DMCC_ID" => $this->DMCC_ID,
			"Title" => $this->Title,
			"Abstract" => $this->Abstract,
			"BiomarkerPopulationCharacteristics" => $this->BiomarkerPopulationCharacteristics,
			"Design" => $this->Design,
			"BiomarkerStudyType" => $this->BiomarkerStudyType,
		);
	}
}

class dao_study {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `study` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchstudyException("No study found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `study` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `study` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `study`"; 
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
		$q = "DELETE FROM `study` WHERE ID=\"$vo->ID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `study` SET ";
		$q .= "ID=\"$vo->ID\"" . ", ";
		$q .= "EDRNID=\"$vo->EDRNID\"" . ", ";
		$q .= "FHCRC_ID=\"$vo->FHCRC_ID\"" . ", ";
		$q .= "DMCC_ID=\"$vo->DMCC_ID\"" . ", ";
		$q .= "Title=\"$vo->Title\"" . ", ";
		$q .= "Abstract=\"$vo->Abstract\"" . ", ";
		$q .= "BiomarkerPopulationCharacteristics=\"$vo->BiomarkerPopulationCharacteristics\"" . ", ";
		$q .= "Design=\"$vo->Design\"" . ", ";
		$q .= "BiomarkerStudyType=\"$vo->BiomarkerStudyType\" ";
		$q .= "WHERE ID=\"$vo->ID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `study` "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->EDRNID.'","'.$vo->FHCRC_ID.'","'.$vo->DMCC_ID.'","'.$vo->Title.'","'.$vo->Abstract.'","'.$vo->BiomarkerPopulationCharacteristics.'","'.$vo->Design.'","'.$vo->BiomarkerStudyType.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `study`");
	}

	public function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->EDRNID = $result['EDRNID'];
		$vo->FHCRC_ID = $result['FHCRC_ID'];
		$vo->DMCC_ID = $result['DMCC_ID'];
		$vo->Title = $result['Title'];
		$vo->Abstract = $result['Abstract'];
		$vo->BiomarkerPopulationCharacteristics = $result['BiomarkerPopulationCharacteristics'];
		$vo->Design = $result['Design'];
		$vo->BiomarkerStudyType = $result['BiomarkerStudyType'];
	}

}

class NoSuchstudyException extends Exception {
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