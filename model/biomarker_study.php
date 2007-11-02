<?php
class biomarker_study {

	public static function Create($BiomarkerID,$StudyID) {
		$vo = new vo_biomarker_study();
		$vo->BiomarkerID = $BiomarkerID;
		$vo->StudyID = $StudyID;
		$dao = new dao_biomarker_study();
		$dao->save(&$vo);
		$obj = new biomarker_study_object();
		$obj->BiomarkerID = $vo->BiomarkerID;
		$obj->StudyID = $vo->StudyID;
		$obj->Sensitivity = $vo->Sensitivity;
		$obj->Specificity = $vo->Specificity;
		$obj->PPV = $vo->PPV;
		$obj->NPV = $vo->NPV;
		$obj->Assay = $vo->Assay;
		$obj->Technology = $vo->Technology;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_biomarker_study();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchbiomarker_studyException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new biomarker_study_object();
				$o->BiomarkerID = $r->BiomarkerID;
				$o->StudyID = $r->StudyID;
				$o->Sensitivity = $r->Sensitivity;
				$o->Specificity = $r->Specificity;
				$o->PPV = $r->PPV;
				$o->NPV = $r->NPV;
				$o->Assay = $r->Assay;
				$o->Technology = $r->Technology;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->study = '';
					$o->biomarker = '';
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->study = '';
						$o->biomarker = '';
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->study = study::Retrieve(array("ID"),array("$o->StudyID"),$po,$lazyFetch,1);
							if ($o->study == null){$o->study = '';}
						} catch (NoSuchstudyException $e){
							// No results matched the query -- silently ignore
							$o->study = '';
						}
						try{
							$o->biomarker = biomarker::Retrieve(array("ID"),array("$o->BiomarkerID"),$po,$lazyFetch,1);
							if ($o->biomarker == null){$o->biomarker = '';}
						} catch (NoSuchbiomarkerException $e){
							// No results matched the query -- silently ignore
							$o->biomarker = '';
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
		if ($objInstance->study != ''){ study::Delete(&$objInstance->study);}
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_biomarker_study();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_biomarker_study;
		$dao->save(&$vo);
	}
	public static function set_study($object,$study){
		$object->study = $study;
	}
}

class biomarker_study_object {

	public $cr_object_type = "biomarker_study";
	public $BiomarkerID = '';
	public $StudyID = '';
	public $Sensitivity = '';
	public $Specificity = '';
	public $PPV = '';
	public $NPV = '';
	public $Assay = '';
	public $Technology = '';
	public $study = '';
	public $biomarker = '';

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "biomarker_study";
	}

	// Accessor Functions (get)
	public function get_BiomarkerID() {
		 return $this->BiomarkerID;
	}
	public function get_StudyID() {
		 return $this->StudyID;
	}
	public function get_Sensitivity() {
		 return $this->Sensitivity;
	}
	public function get_Specificity() {
		 return $this->Specificity;
	}
	public function get_PPV() {
		 return $this->PPV;
	}
	public function get_NPV() {
		 return $this->NPV;
	}
	public function get_Assay() {
		 return $this->Assay;
	}
	public function get_Technology() {
		 return $this->Technology;
	}

	// Mutator Functions (set)
	public function set_BiomarkerID($value) {
		$this->BiomarkerID = $value;
	}
	public function set_StudyID($value) {
		$this->StudyID = $value;
	}
	public function set_Sensitivity($value) {
		$this->Sensitivity = $value;
	}
	public function set_Specificity($value) {
		$this->Specificity = $value;
	}
	public function set_PPV($value) {
		$this->PPV = $value;
	}
	public function set_NPV($value) {
		$this->NPV = $value;
	}
	public function set_Assay($value) {
		$this->Assay = $value;
	}
	public function set_Technology($value) {
		$this->Technology = $value;
	}


	public function equals($objArray){
		if ($objArray == null){return false;}
		foreach ($objArray as $obj){
			//echo "::EQUALS:: comparing $this->cr_object_type WITH $obj->cr_object_type &nbsp;";
			// Check object types first
			if ($this->cr_object_type == $obj->cr_object_type){
				// Check each primary key next
				if ($this->BiomarkerID != $obj->BiomarkerID){continue;}
				if ($this->StudyID != $obj->StudyID){continue;}
				return true;
			}
		}
		return false;
	}
	public function getVO() {
		$vo = new vo_biomarker_study();
		$vo->BiomarkerID = $this->BiomarkerID;
		$vo->StudyID = $this->StudyID;
		$vo->Sensitivity = $this->Sensitivity;
		$vo->Specificity = $this->Specificity;
		$vo->PPV = $this->PPV;
		$vo->NPV = $this->NPV;
		$vo->Assay = $this->Assay;
		$vo->Technology = $this->Technology;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		if ($this->study != ''){$rdf .= $this->study->toRDFStub($namespace,$urlBase);}
		if ($this->biomarker != ''){$rdf .= $this->biomarker->toRDFStub($namespace,$urlBase);}
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		if($this->study != ''){$rdf .= $this->study->toRDFStub($namespace,$urlBase);}
		if($this->biomarker != ''){$rdf .= $this->biomarker->toRDFStub($namespace,$urlBase);}
		return $rdf;
	}
}

class vo_biomarker_study {
	public $BiomarkerID;
	public $StudyID;
	public $Sensitivity;
	public $Specificity;
	public $PPV;
	public $NPV;
	public $Assay;
	public $Technology;

	public function toAssocArray(){
		return array(
			"BiomarkerID" => $this->BiomarkerID,
			"StudyID" => $this->StudyID,
			"Sensitivity" => $this->Sensitivity,
			"Specificity" => $this->Specificity,
			"PPV" => $this->PPV,
			"NPV" => $this->NPV,
			"Assay" => $this->Assay,
			"Technology" => $this->Technology,
		);
	}
}

class dao_biomarker_study {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("BiomarkerID","StudyID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `biomarker_study` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchbiomarker_studyException("No biomarker_study found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `biomarker_study` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `biomarker_study` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `biomarker_study`"; 
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
		$q = "DELETE FROM `biomarker_study` WHERE BiomarkerID=\"$vo->BiomarkerID\" AND StudyID=\"$vo->StudyID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->BiomarkerID=0;
		$vo->StudyID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `biomarker_study` SET ";
		$q .= "BiomarkerID=\"$vo->BiomarkerID\"" . ", ";
		$q .= "StudyID=\"$vo->StudyID\"" . ", ";
		$q .= "Sensitivity=\"$vo->Sensitivity\"" . ", ";
		$q .= "Specificity=\"$vo->Specificity\"" . ", ";
		$q .= "PPV=\"$vo->PPV\"" . ", ";
		$q .= "NPV=\"$vo->NPV\"" . ", ";
		$q .= "Assay=\"$vo->Assay\"" . ", ";
		$q .= "Technology=\"$vo->Technology\" ";
		$q .= "WHERE BiomarkerID=\"$vo->BiomarkerID\" AND StudyID=\"$vo->StudyID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `biomarker_study` "; 
		$q .= 'VALUES("'.$vo->BiomarkerID.'","'.$vo->StudyID.'","'.$vo->Sensitivity.'","'.$vo->Specificity.'","'.$vo->PPV.'","'.$vo->NPV.'","'.$vo->Assay.'","'.$vo->Technology.'" ) ';
		$r = $this->conn->safeQuery($q);
	}

	public function getFromResult(&$vo,$result){
		$vo->BiomarkerID = $result['BiomarkerID'];
		$vo->StudyID = $result['StudyID'];
		$vo->Sensitivity = $result['Sensitivity'];
		$vo->Specificity = $result['Specificity'];
		$vo->PPV = $result['PPV'];
		$vo->NPV = $result['NPV'];
		$vo->Assay = $result['Assay'];
		$vo->Technology = $result['Technology'];
	}

}

class NoSuchbiomarker_studyException extends Exception {
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