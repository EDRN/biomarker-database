<?php
class publication {

	public static function Create($PubMedID) {
		$vo = new vo_publication();
		$vo->PubMedID = $PubMedID;
		$dao = new dao_publication();
		$dao->save(&$vo);
		$obj = new publication_object();
		$obj->ID = $vo->ID;
		$obj->PubMedID = $vo->PubMedID;
		$obj->Title = $vo->Title;
		$obj->Author = $vo->Author;
		$obj->Journal = $vo->Journal;
		$obj->Volume = $vo->Volume;
		$obj->Issue = $vo->Issue;
		$obj->Year = $vo->Year;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_publication();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchpublicationException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new publication_object();
				$o->ID = $r->ID;
				$o->PubMedID = $r->PubMedID;
				$o->Title = $r->Title;
				$o->Author = $r->Author;
				$o->Journal = $r->Journal;
				$o->Volume = $r->Volume;
				$o->Issue = $r->Issue;
				$o->Year = $r->Year;
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
							$o->biomarkers = biomarker_publication::Retrieve(array("PublicationID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->biomarkers == null){$o->biomarkers = array();}
						} catch (NoSuchbiomarker_publicationException $e){
							// No results matched the query -- silently ignore
							$o->biomarkers = array();
						}
						try{
							$o->biomarker_organs = biomarker_organ_publication::Retrieve(array("PublicationID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->biomarker_organs == null){$o->biomarker_organs = array();}
						} catch (NoSuchbiomarker_organ_publicationException $e){
							// No results matched the query -- silently ignore
							$o->biomarker_organs = array();
						}
						try{
							$o->studies = study_publication::Retrieve(array("PublicationID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->studies == null){$o->studies = array();}
						} catch (NoSuchstudy_publicationException $e){
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
		$dao = new dao_publication();
		try {
			$results = $dao->getBy(array("ID"),array("$ID"));
		} catch (NoSuchpublicationException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new publication_object();
		$obj->ID = $results[0]->ID;
		$obj->PubMedID = $results[0]->PubMedID;
		$obj->Title = $results[0]->Title;
		$obj->Author = $results[0]->Author;
		$obj->Journal = $results[0]->Journal;
		$obj->Volume = $results[0]->Volume;
		$obj->Issue = $results[0]->Issue;
		$obj->Year = $results[0]->Year;
		if ($lazyFetch == true){
			$obj->biomarkers = array();
			$obj->biomarker_organs = array();
			$obj->studies = array();
		} else {
			$obj->biomarkers = biomarker_publication::Retrieve(array("PublicationID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->biomarkers == null){$obj->biomarkers = array();}
			$obj->biomarker_organs = biomarker_organ_publication::Retrieve(array("PublicationID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->biomarker_organs == null){$obj->biomarker_organs = array();}
			$obj->studies = study_publication::Retrieve(array("PublicationID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->studies == null){$obj->studies = array();}
		}
		return $obj;
	}
	public static function Delete(&$objInstance) {
		// Delete any peers and/or children that should be removed
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_publication();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_publication;
		$dao->save(&$vo);
	}
	public static function add_biomarker_publication($object,$biomarker_publication){
		$object->biomarkers[] = $biomarker_publication;
	}
	public static function add_biomarker_organ_publication($object,$biomarker_organ_publication){
		$object->biomarker_organs[] = $biomarker_organ_publication;
	}
	public static function add_study_publication($object,$study_publication){
		$object->studies[] = $study_publication;
	}
}

class publication_object {

	public $cr_object_type = "publication";
	public $ID = '';
	public $PubMedID = '';
	public $Title = '';
	public $Author = '';
	public $Journal = '';
	public $Volume = '';
	public $Issue = '';
	public $Year = '';
	public $biomarkers = array();
	public $biomarker_organs = array();
	public $studies = array();

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "publication";
	}

	// Accessor Functions (get)
	public function get_ID() {
		 return $this->ID;
	}
	public function get_PubMedID() {
		 return $this->PubMedID;
	}
	public function get_Title() {
		 return $this->Title;
	}
	public function get_Author() {
		 return $this->Author;
	}
	public function get_Journal() {
		 return $this->Journal;
	}
	public function get_Volume() {
		 return $this->Volume;
	}
	public function get_Issue() {
		 return $this->Issue;
	}
	public function get_Year() {
		 return $this->Year;
	}

	// Mutator Functions (set)
	public function set_ID($value) {
		$this->ID = $value;
	}
	public function set_PubMedID($value) {
		$this->PubMedID = $value;
	}
	public function set_Title($value) {
		$this->Title = $value;
	}
	public function set_Author($value) {
		$this->Author = $value;
	}
	public function set_Journal($value) {
		$this->Journal = $value;
	}
	public function set_Volume($value) {
		$this->Volume = $value;
	}
	public function set_Issue($value) {
		$this->Issue = $value;
	}
	public function set_Year($value) {
		$this->Year = $value;
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
		$vo = new vo_publication();
		$vo->ID = $this->ID;
		$vo->PubMedID = $this->PubMedID;
		$vo->Title = $this->Title;
		$vo->Author = $this->Author;
		$vo->Journal = $this->Journal;
		$vo->Volume = $this->Volume;
		$vo->Issue = $this->Issue;
		$vo->Year = $this->Year;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:publication rdf:about=\"{$urlBase}/editors/showPublication.php?p={$this->ID}\">\r\n<{$namespace}:ID>$this->ID</{$namespace}:ID>\r\n<{$namespace}:PubMedID>$this->PubMedID</{$namespace}:PubMedID>\r\n<{$namespace}:Title>$this->Title</{$namespace}:Title>\r\n<{$namespace}:Author>$this->Author</{$namespace}:Author>\r\n<{$namespace}:Journal>$this->Journal</{$namespace}:Journal>\r\n<{$namespace}:Volume>$this->Volume</{$namespace}:Volume>\r\n<{$namespace}:Issue>$this->Issue</{$namespace}:Issue>\r\n<{$namespace}:Year>$this->Year</{$namespace}:Year>\r\n";
		foreach ($this->biomarkers as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->biomarker_organs as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->studies as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:publication>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:publication rdf:about=\"{$urlBase}/editors/showPublication.php?p={$this->ID}\"/>\r\n";
		return $rdf;
	}
}

class vo_publication {
	public $ID;
	public $PubMedID;
	public $Title;
	public $Author;
	public $Journal;
	public $Volume;
	public $Issue;
	public $Year;

	public function toAssocArray(){
		return array(
			"ID" => $this->ID,
			"PubMedID" => $this->PubMedID,
			"Title" => $this->Title,
			"Author" => $this->Author,
			"Journal" => $this->Journal,
			"Volume" => $this->Volume,
			"Issue" => $this->Issue,
			"Year" => $this->Year,
		);
	}
}

class dao_publication {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `publication` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchpublicationException("No publication found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_publication();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `publication` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_publication();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `publication` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_publication();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `publication`"; 
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
		$q = "DELETE FROM `publication` WHERE ID=\"$vo->ID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `publication` SET ";
		$q .= "ID=\"$vo->ID\"" . ", ";
		$q .= "PubMedID=\"$vo->PubMedID\"" . ", ";
		$q .= "Title=\"$vo->Title\"" . ", ";
		$q .= "Author=\"$vo->Author\"" . ", ";
		$q .= "Journal=\"$vo->Journal\"" . ", ";
		$q .= "Volume=\"$vo->Volume\"" . ", ";
		$q .= "Issue=\"$vo->Issue\"" . ", ";
		$q .= "Year=\"$vo->Year\" ";
		$q .= "WHERE ID=\"$vo->ID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `publication` "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->PubMedID.'","'.$vo->Title.'","'.$vo->Author.'","'.$vo->Journal.'","'.$vo->Volume.'","'.$vo->Issue.'","'.$vo->Year.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `publication`");
	}

	public function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->PubMedID = $result['PubMedID'];
		$vo->Title = $result['Title'];
		$vo->Author = $result['Author'];
		$vo->Journal = $result['Journal'];
		$vo->Volume = $result['Volume'];
		$vo->Issue = $result['Issue'];
		$vo->Year = $result['Year'];
	}

}

class NoSuchpublicationException extends Exception {
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