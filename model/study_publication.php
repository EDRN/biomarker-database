<?php
class study_publication {

	public static function Create($PublicationID,$StudyID) {
		$vo = new vo_study_publication();
		$vo->PublicationID = $PublicationID;
		$vo->StudyID = $StudyID;
		$dao = new dao_study_publication();
		$dao->save(&$vo);
		$obj = new study_publication_object();
		$obj->PublicationID = $vo->PublicationID;
		$obj->StudyID = $vo->StudyID;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_study_publication();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchstudy_publicationException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new study_publication_object();
				$o->PublicationID = $r->PublicationID;
				$o->StudyID = $r->StudyID;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->publication = '';
					$o->study = '';
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->publication = '';
						$o->study = '';
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->publication = publication::Retrieve(array("ID"),array("$o->PublicationID"),$po,$lazyFetch,1);
							if ($o->publication == null){$o->publication = '';}
						} catch (NoSuchpublicationException $e){
							// No results matched the query -- silently ignore
							$o->publication = '';
						}
						try{
							$o->study = study::Retrieve(array("ID"),array("$o->StudyID"),$po,$lazyFetch,1);
							if ($o->study == null){$o->study = '';}
						} catch (NoSuchstudyException $e){
							// No results matched the query -- silently ignore
							$o->study = '';
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
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_study_publication();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_study_publication;
		$dao->save(&$vo);
	}
}

class study_publication_object {

	public $cr_object_type = "study_publication";
	public $PublicationID = '';
	public $StudyID = '';
	public $publication = '';
	public $study = '';

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "study_publication";
	}

	// Accessor Functions (get)
	public function get_PublicationID() {
		 return $this->PublicationID;
	}
	public function get_StudyID() {
		 return $this->StudyID;
	}

	// Mutator Functions (set)
	public function set_PublicationID($value) {
		$this->PublicationID = $value;
	}
	public function set_StudyID($value) {
		$this->StudyID = $value;
	}


	public function equals($objArray){
		if ($objArray == null){return false;}
		foreach ($objArray as $obj){
			//echo "::EQUALS:: comparing $this->cr_object_type WITH $obj->cr_object_type &nbsp;";
			// Check object types first
			if ($this->cr_object_type == $obj->cr_object_type){
				// Check each primary key next
				if ($this->PublicationID != $obj->PublicationID){continue;}
				if ($this->StudyID != $obj->StudyID){continue;}
				return true;
			}
		}
		return false;
	}
	public function getVO() {
		$vo = new vo_study_publication();
		$vo->PublicationID = $this->PublicationID;
		$vo->StudyID = $this->StudyID;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		if ($this->publication != ''){$rdf .= $this->publication->toRDFStub($namespace,$urlBase);}
		if ($this->study != ''){$rdf .= $this->study->toRDFStub($namespace,$urlBase);}
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		if($this->publication != ''){$rdf .= $this->publication->toRDFStub($namespace,$urlBase);}
		if($this->study != ''){$rdf .= $this->study->toRDFStub($namespace,$urlBase);}
		return $rdf;
	}
}

class vo_study_publication {
	public $PublicationID;
	public $StudyID;

	public function toAssocArray(){
		return array(
			"PublicationID" => $this->PublicationID,
			"StudyID" => $this->StudyID,
		);
	}
}

class dao_study_publication {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("PublicationID","StudyID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `study_publication` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchstudy_publicationException("No study_publication found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_study_publication();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `study_publication` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_study_publication();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `study_publication` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_study_publication();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `study_publication`"; 
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
		$q = "DELETE FROM `study_publication` WHERE PublicationID=\"$vo->PublicationID\" AND StudyID=\"$vo->StudyID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->PublicationID=0;
		$vo->StudyID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `study_publication` SET ";
		$q .= "PublicationID=\"$vo->PublicationID\"" . ", ";
		$q .= "StudyID=\"$vo->StudyID\" ";
		$q .= "WHERE PublicationID=\"$vo->PublicationID\" AND StudyID=\"$vo->StudyID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `study_publication` "; 
		$q .= 'VALUES("'.$vo->PublicationID.'","'.$vo->StudyID.'" ) ';
		$r = $this->conn->safeQuery($q);
	}

	public function getFromResult(&$vo,$result){
		$vo->PublicationID = $result['PublicationID'];
		$vo->StudyID = $result['StudyID'];
	}

}

class NoSuchstudy_publicationException extends Exception {
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