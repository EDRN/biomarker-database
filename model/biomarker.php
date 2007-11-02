<?php
class biomarker {

	public static function Create($Title) {
		$vo = new vo_biomarker();
		$vo->Title = $Title;
		$dao = new dao_biomarker();
		$dao->save(&$vo);
		$obj = new biomarker_object();
		$obj->ID = $vo->ID;
		$obj->EKE_ID = $vo->EKE_ID;
		$obj->BiomarkerID = $vo->BiomarkerID;
		$obj->PanelID = $vo->PanelID;
		$obj->Title = $vo->Title;
		$obj->Description = $vo->Description;
		$obj->QAState = $vo->QAState;
		$obj->Phase = $vo->Phase;
		$obj->Security = $vo->Security;
		$obj->Type = $vo->Type;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_biomarker();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchbiomarkerException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new biomarker_object();
				$o->ID = $r->ID;
				$o->EKE_ID = $r->EKE_ID;
				$o->BiomarkerID = $r->BiomarkerID;
				$o->PanelID = $r->PanelID;
				$o->Title = $r->Title;
				$o->Description = $r->Description;
				$o->QAState = $r->QAState;
				$o->Phase = $r->Phase;
				$o->Security = $r->Security;
				$o->Type = $r->Type;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->aliases = array();
					$o->studies = array();
					$o->organs = array();
					$o->publications = array();
					$o->resources = array();
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->aliases = array();
						$o->studies = array();
						$o->organs = array();
						$o->publications = array();
						$o->resources = array();
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->aliases = biomarker_alias::Retrieve(array("BiomarkerID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->aliases == null){$o->aliases = array();}
						} catch (NoSuchbiomarker_aliasException $e){
							// No results matched the query -- silently ignore
							$o->aliases = array();
						}
						try{
							$o->studies = biomarker_study::Retrieve(array("BiomarkerID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->studies == null){$o->studies = array();}
						} catch (NoSuchbiomarker_studyException $e){
							// No results matched the query -- silently ignore
							$o->studies = array();
						}
						try{
							$o->organs = biomarker_organ::Retrieve(array("BiomarkerID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->organs == null){$o->organs = array();}
						} catch (NoSuchbiomarker_organException $e){
							// No results matched the query -- silently ignore
							$o->organs = array();
						}
						try{
							$o->publications = biomarker_publication::Retrieve(array("BiomarkerID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->publications == null){$o->publications = array();}
						} catch (NoSuchbiomarker_publicationException $e){
							// No results matched the query -- silently ignore
							$o->publications = array();
						}
						try{
							$o->resources = biomarker_resource::Retrieve(array("BiomarkerID"),array("$o->ID"),$po,$lazyFetch,0);
							if ($o->resources == null){$o->resources = array();}
						} catch (NoSuchbiomarker_resourceException $e){
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
		$dao = new dao_biomarker();
		try {
			$results = $dao->getBy(array("ID"),array("$ID"));
		} catch (NoSuchbiomarkerException $e){
			// No results matched the query -- silently ignore, for now
		}
		if (sizeof($results) != 1){/* TODO: Duplicate or non-existant key. Should throw an error here */}
		$obj = new biomarker_object();
		$obj->ID = $results[0]->ID;
		$obj->EKE_ID = $results[0]->EKE_ID;
		$obj->BiomarkerID = $results[0]->BiomarkerID;
		$obj->PanelID = $results[0]->PanelID;
		$obj->Title = $results[0]->Title;
		$obj->Description = $results[0]->Description;
		$obj->QAState = $results[0]->QAState;
		$obj->Phase = $results[0]->Phase;
		$obj->Security = $results[0]->Security;
		$obj->Type = $results[0]->Type;
		if ($lazyFetch == true){
			$obj->aliases = array();
			$obj->studies = array();
			$obj->organs = array();
			$obj->publications = array();
			$obj->resources = array();
		} else {
			$obj->aliases = biomarker_alias::Retrieve(array("BiomarkerID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->aliases == null){$obj->aliases = array();}
			$obj->studies = biomarker_study::Retrieve(array("BiomarkerID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->studies == null){$obj->studies = array();}
			$obj->organs = biomarker_organ::Retrieve(array("BiomarkerID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->organs == null){$obj->organs = array();}
			$obj->publications = biomarker_publication::Retrieve(array("BiomarkerID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->publications == null){$obj->publications = array();}
			$obj->resources = biomarker_resource::Retrieve(array("BiomarkerID"),array("$obj->ID"),array(&$obj),$lazyFetch);
			if ($obj->resources == null){$obj->resources = array();}
		}
		return $obj;
	}
	public static function Delete(&$objInstance) {
		// Delete any peers and/or children that should be removed
		foreach ($objInstance->aliases as $deleteme) {
			if($deleteme != array()){ biomarker_alias::Delete(&$deleteme);}
		}
		// Delete the object itself
		$vo  = $objInstance->getVO();
		$dao = new dao_biomarker();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_biomarker;
		$dao->save(&$vo);
	}
	public static function add_biomarker_alias($object,$biomarker_alias){
		$object->aliases[] = $biomarker_alias;
	}
	public static function add_biomarker_study($object,$biomarker_study){
		$object->studies[] = $biomarker_study;
	}
	public static function add_biomarker_organ($object,$biomarker_organ){
		$object->organs[] = $biomarker_organ;
	}
	public static function add_biomarker_publication($object,$biomarker_publication){
		$object->publications[] = $biomarker_publication;
	}
	public static function add_biomarker_resource($object,$biomarker_resource){
		$object->resources[] = $biomarker_resource;
	}
}

class biomarker_object {

	public $cr_object_type = "biomarker";
	public $ID = '';
	public $EKE_ID = '';
	public $BiomarkerID = '';
	public $PanelID = '';
	public $Title = '';
	public $Description = '';
	public $QAState = '';
	public $Phase = '';
	public $Security = '';
	public $Type = '';
	public $aliases = array();
	public $studies = array();
	public $organs = array();
	public $publications = array();
	public $resources = array();

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "biomarker";
	}

	// Accessor Functions (get)
	public function get_ID() {
		 return $this->ID;
	}
	public function get_EKE_ID() {
		 return $this->EKE_ID;
	}
	public function get_BiomarkerID() {
		 return $this->BiomarkerID;
	}
	public function get_PanelID() {
		 return $this->PanelID;
	}
	public function get_Title() {
		 return $this->Title;
	}
	public function get_Description() {
		 return $this->Description;
	}
	public function get_QAState() {
		 return $this->QAState;
	}
	public function get_Phase() {
		 return $this->Phase;
	}
	public function get_Security() {
		 return $this->Security;
	}
	public function get_Type() {
		 return $this->Type;
	}

	// Mutator Functions (set)
	public function set_ID($value) {
		$this->ID = $value;
	}
	public function set_EKE_ID($value) {
		$this->EKE_ID = $value;
	}
	public function set_BiomarkerID($value) {
		$this->BiomarkerID = $value;
	}
	public function set_PanelID($value) {
		$this->PanelID = $value;
	}
	public function set_Title($value) {
		$this->Title = $value;
	}
	public function set_Description($value) {
		$this->Description = $value;
	}
	public function set_QAState($value) {
		$this->QAState = $value;
	}
	public function set_Phase($value) {
		$this->Phase = $value;
	}
	public function set_Security($value) {
		$this->Security = $value;
	}
	public function set_Type($value) {
		$this->Type = $value;
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
		$vo = new vo_biomarker();
		$vo->ID = $this->ID;
		$vo->EKE_ID = $this->EKE_ID;
		$vo->BiomarkerID = $this->BiomarkerID;
		$vo->PanelID = $this->PanelID;
		$vo->Title = $this->Title;
		$vo->Description = $this->Description;
		$vo->QAState = $this->QAState;
		$vo->Phase = $this->Phase;
		$vo->Security = $this->Security;
		$vo->Type = $this->Type;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:biomarker rdf:about=\"{$urlBase}/editors/showBiomarker.php?m={$this->ID}\">\r\n<{$namespace}:ID>$this->ID</{$namespace}:ID>\r\n<{$namespace}:EKE_ID>$this->EKE_ID</{$namespace}:EKE_ID>\r\n<{$namespace}:BiomarkerID>$this->BiomarkerID</{$namespace}:BiomarkerID>\r\n<{$namespace}:PanelID>$this->PanelID</{$namespace}:PanelID>\r\n<{$namespace}:Title>$this->Title</{$namespace}:Title>\r\n<{$namespace}:Description>$this->Description</{$namespace}:Description>\r\n<{$namespace}:QAState>$this->QAState</{$namespace}:QAState>\r\n<{$namespace}:Phase>$this->Phase</{$namespace}:Phase>\r\n<{$namespace}:Security>$this->Security</{$namespace}:Security>\r\n<{$namespace}:Type>$this->Type</{$namespace}:Type>\r\n";
		foreach ($this->aliases as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->studies as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->organs as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->publications as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->resources as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:biomarker>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:biomarker rdf:about=\"{$urlBase}/editors/showBiomarker.php?m={$this->ID}\"/>\r\n";
		return $rdf;
	}
}

class vo_biomarker {
	public $ID;
	public $EKE_ID;
	public $BiomarkerID;
	public $PanelID;
	public $Title;
	public $Description;
	public $QAState;
	public $Phase;
	public $Security;
	public $Type;

	public function toAssocArray(){
		return array(
			"ID" => $this->ID,
			"EKE_ID" => $this->EKE_ID,
			"BiomarkerID" => $this->BiomarkerID,
			"PanelID" => $this->PanelID,
			"Title" => $this->Title,
			"Description" => $this->Description,
			"QAState" => $this->QAState,
			"Phase" => $this->Phase,
			"Security" => $this->Security,
			"Type" => $this->Type,
		);
	}
}

class dao_biomarker {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("ID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `biomarker` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchbiomarkerException("No biomarker found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `biomarker` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `biomarker` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `biomarker`"; 
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
		$q = "DELETE FROM `biomarker` WHERE ID=\"$vo->ID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `biomarker` SET ";
		$q .= "ID=\"$vo->ID\"" . ", ";
		$q .= "EKE_ID=\"$vo->EKE_ID\"" . ", ";
		$q .= "BiomarkerID=\"$vo->BiomarkerID\"" . ", ";
		$q .= "PanelID=\"$vo->PanelID\"" . ", ";
		$q .= "Title=\"$vo->Title\"" . ", ";
		$q .= "Description=\"$vo->Description\"" . ", ";
		$q .= "QAState=\"$vo->QAState\"" . ", ";
		$q .= "Phase=\"$vo->Phase\"" . ", ";
		$q .= "Security=\"$vo->Security\"" . ", ";
		$q .= "Type=\"$vo->Type\" ";
		$q .= "WHERE ID=\"$vo->ID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `biomarker` "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->EKE_ID.'","'.$vo->BiomarkerID.'","'.$vo->PanelID.'","'.$vo->Title.'","'.$vo->Description.'","'.$vo->QAState.'","'.$vo->Phase.'","'.$vo->Security.'","'.$vo->Type.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `biomarker`");
	}

	public function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->EKE_ID = $result['EKE_ID'];
		$vo->BiomarkerID = $result['BiomarkerID'];
		$vo->PanelID = $result['PanelID'];
		$vo->Title = $result['Title'];
		$vo->Description = $result['Description'];
		$vo->QAState = $result['QAState'];
		$vo->Phase = $result['Phase'];
		$vo->Security = $result['Security'];
		$vo->Type = $result['Type'];
	}

}

class NoSuchbiomarkerException extends Exception {
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