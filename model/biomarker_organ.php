<?php
class biomarker_organ {

	public static function Create($BiomarkerID,$OrganSite) {
		$vo = new vo_biomarker_organ();
		$vo->BiomarkerID = $BiomarkerID;
		$vo->OrganSite = $OrganSite;
		$dao = new dao_biomarker_organ();
		$dao->save(&$vo);
		$obj = new biomarker_organ_object();
		$obj->BiomarkerID = $vo->BiomarkerID;
		$obj->OrganSite = $vo->OrganSite;
		$obj->SensitivityMin = $vo->SensitivityMin;
		$obj->SensitivityMax = $vo->SensitivityMax;
		$obj->SensitivityComment = $vo->SensitivityComment;
		$obj->SpecificityMin = $vo->SpecificityMin;
		$obj->SpecificityMax = $vo->SpecificityMax;
		$obj->SpecificityComment = $vo->SpecificityComment;
		$obj->PPVMin = $vo->PPVMin;
		$obj->PPVMax = $vo->PPVMax;
		$obj->PPVComment = $vo->PPVComment;
		$obj->NPVMin = $vo->NPVMin;
		$obj->NPVMax = $vo->NPVMax;
		$obj->NPVComment = $vo->NPVComment;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_biomarker_organ();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchbiomarker_organException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new biomarker_organ_object();
				$o->BiomarkerID = $r->BiomarkerID;
				$o->OrganSite = $r->OrganSite;
				$o->SensitivityMin = $r->SensitivityMin;
				$o->SensitivityMax = $r->SensitivityMax;
				$o->SensitivityComment = $r->SensitivityComment;
				$o->SpecificityMin = $r->SpecificityMin;
				$o->SpecificityMax = $r->SpecificityMax;
				$o->SpecificityComment = $r->SpecificityComment;
				$o->PPVMin = $r->PPVMin;
				$o->PPVMax = $r->PPVMax;
				$o->PPVComment = $r->PPVComment;
				$o->NPVMin = $r->NPVMin;
				$o->NPVMax = $r->NPVMax;
				$o->NPVComment = $r->NPVComment;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->organ = '';
					$o->biomarker = '';
					$o->resources = array();
					$o->publications = array();
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->organ = '';
						$o->biomarker = '';
						$o->resources = array();
						$o->publications = array();
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->organ = organ::Retrieve(array("ID"),array("$o->OrganSite"),$po,$lazyFetch,1);
							if ($o->organ == null){$o->organ = '';}
						} catch (NoSuchorganException $e){
							// No results matched the query -- silently ignore
							$o->organ = '';
						}
						try{
							$o->biomarker = biomarker::Retrieve(array("ID"),array("$o->BiomarkerID"),$po,$lazyFetch,1);
							if ($o->biomarker == null){$o->biomarker = '';}
						} catch (NoSuchbiomarkerException $e){
							// No results matched the query -- silently ignore
							$o->biomarker = '';
						}
						try{
							$o->resources = biomarker_organ_resource::Retrieve(array("BiomarkerID","OrganSite"),array("$o->BiomarkerID","$o->OrganSite"),$po,$lazyFetch,0);
							if ($o->resources == null){$o->resources = array();}
						} catch (NoSuchbiomarker_organ_resourceException $e){
							// No results matched the query -- silently ignore
							$o->resources = array();
						}
						try{
							$o->publications = biomarker_organ_publication::Retrieve(array("BiomarkerID","OrganSite"),array("$o->BiomarkerID","$o->OrganSite"),$po,$lazyFetch,0);
							if ($o->publications == null){$o->publications = array();}
						} catch (NoSuchbiomarker_organ_publicationException $e){
							// No results matched the query -- silently ignore
							$o->publications = array();
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
		$dao = new dao_biomarker_organ();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_biomarker_organ;
		$dao->save(&$vo);
	}
	public static function add_biomarker_organ_resource($object,$biomarker_organ_resource){
		$object->resources[] = $biomarker_organ_resource;
	}
	public static function add_biomarker_organ_publication($object,$biomarker_organ_publication){
		$object->publications[] = $biomarker_organ_publication;
	}
}

class biomarker_organ_object {

	public $cr_object_type = "biomarker_organ";
	public $BiomarkerID = '';
	public $OrganSite = '';
	public $SensitivityMin = '';
	public $SensitivityMax = '';
	public $SensitivityComment = '';
	public $SpecificityMin = '';
	public $SpecificityMax = '';
	public $SpecificityComment = '';
	public $PPVMin = '';
	public $PPVMax = '';
	public $PPVComment = '';
	public $NPVMin = '';
	public $NPVMax = '';
	public $NPVComment = '';
	public $organ = '';
	public $biomarker = '';
	public $resources = array();
	public $publications = array();

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "biomarker_organ";
	}

	// Accessor Functions (get)
	public function get_BiomarkerID() {
		 return $this->BiomarkerID;
	}
	public function get_OrganSite() {
		 return $this->OrganSite;
	}
	public function get_SensitivityMin() {
		 return $this->SensitivityMin;
	}
	public function get_SensitivityMax() {
		 return $this->SensitivityMax;
	}
	public function get_SensitivityComment() {
		 return $this->SensitivityComment;
	}
	public function get_SpecificityMin() {
		 return $this->SpecificityMin;
	}
	public function get_SpecificityMax() {
		 return $this->SpecificityMax;
	}
	public function get_SpecificityComment() {
		 return $this->SpecificityComment;
	}
	public function get_PPVMin() {
		 return $this->PPVMin;
	}
	public function get_PPVMax() {
		 return $this->PPVMax;
	}
	public function get_PPVComment() {
		 return $this->PPVComment;
	}
	public function get_NPVMin() {
		 return $this->NPVMin;
	}
	public function get_NPVMax() {
		 return $this->NPVMax;
	}
	public function get_NPVComment() {
		 return $this->NPVComment;
	}

	// Mutator Functions (set)
	public function set_BiomarkerID($value) {
		$this->BiomarkerID = $value;
	}
	public function set_OrganSite($value) {
		$this->OrganSite = $value;
	}
	public function set_SensitivityMin($value) {
		$this->SensitivityMin = $value;
	}
	public function set_SensitivityMax($value) {
		$this->SensitivityMax = $value;
	}
	public function set_SensitivityComment($value) {
		$this->SensitivityComment = $value;
	}
	public function set_SpecificityMin($value) {
		$this->SpecificityMin = $value;
	}
	public function set_SpecificityMax($value) {
		$this->SpecificityMax = $value;
	}
	public function set_SpecificityComment($value) {
		$this->SpecificityComment = $value;
	}
	public function set_PPVMin($value) {
		$this->PPVMin = $value;
	}
	public function set_PPVMax($value) {
		$this->PPVMax = $value;
	}
	public function set_PPVComment($value) {
		$this->PPVComment = $value;
	}
	public function set_NPVMin($value) {
		$this->NPVMin = $value;
	}
	public function set_NPVMax($value) {
		$this->NPVMax = $value;
	}
	public function set_NPVComment($value) {
		$this->NPVComment = $value;
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
				return true;
			}
		}
		return false;
	}
	public function getVO() {
		$vo = new vo_biomarker_organ();
		$vo->BiomarkerID = $this->BiomarkerID;
		$vo->OrganSite = $this->OrganSite;
		$vo->SensitivityMin = $this->SensitivityMin;
		$vo->SensitivityMax = $this->SensitivityMax;
		$vo->SensitivityComment = $this->SensitivityComment;
		$vo->SpecificityMin = $this->SpecificityMin;
		$vo->SpecificityMax = $this->SpecificityMax;
		$vo->SpecificityComment = $this->SpecificityComment;
		$vo->PPVMin = $this->PPVMin;
		$vo->PPVMax = $this->PPVMax;
		$vo->PPVComment = $this->PPVComment;
		$vo->NPVMin = $this->NPVMin;
		$vo->NPVMax = $this->NPVMax;
		$vo->NPVComment = $this->NPVComment;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:biomarker_organ rdf:about=\"{$urlBase}/editors/showBiomarkerOrgan.php?b={$this->BiomarkerID}&amp;o={$this->OrganSite}\">\r\n<{$namespace}:BiomarkerID>$this->BiomarkerID</{$namespace}:BiomarkerID>\r\n<{$namespace}:OrganSite>$this->OrganSite</{$namespace}:OrganSite>\r\n<{$namespace}:SensitivityMin>$this->SensitivityMin</{$namespace}:SensitivityMin>\r\n<{$namespace}:SensitivityMax>$this->SensitivityMax</{$namespace}:SensitivityMax>\r\n<{$namespace}:SensitivityComment>$this->SensitivityComment</{$namespace}:SensitivityComment>\r\n<{$namespace}:SpecificityMin>$this->SpecificityMin</{$namespace}:SpecificityMin>\r\n<{$namespace}:SpecificityMax>$this->SpecificityMax</{$namespace}:SpecificityMax>\r\n<{$namespace}:SpecificityComment>$this->SpecificityComment</{$namespace}:SpecificityComment>\r\n<{$namespace}:PPVMin>$this->PPVMin</{$namespace}:PPVMin>\r\n<{$namespace}:PPVMax>$this->PPVMax</{$namespace}:PPVMax>\r\n<{$namespace}:PPVComment>$this->PPVComment</{$namespace}:PPVComment>\r\n<{$namespace}:NPVMin>$this->NPVMin</{$namespace}:NPVMin>\r\n<{$namespace}:NPVMax>$this->NPVMax</{$namespace}:NPVMax>\r\n<{$namespace}:NPVComment>$this->NPVComment</{$namespace}:NPVComment>\r\n";
		if ($this->organ != ''){$rdf .= $this->organ->toRDFStub($namespace,$urlBase);}
		if ($this->biomarker != ''){$rdf .= $this->biomarker->toRDFStub($namespace,$urlBase);}
		foreach ($this->resources as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}
		foreach ($this->publications as $r) {
			$rdf .= $r->toRDFStub($namespace,$urlBase);
		}

		$rdf .= "</{$namespace}:biomarker_organ>\r\n";
		return $rdf;
	}
	public function toRDFStub($namespace,$urlBase) {
		$rdf = '';
		$rdf .= "<{$namespace}:biomarker_organ rdf:about=\"{$urlBase}/editors/showBiomarkerOrgan.php?b={$this->BiomarkerID}&amp;o={$this->OrganSite}\"/>\r\n";
		return $rdf;
	}
}

class vo_biomarker_organ {
	public $BiomarkerID;
	public $OrganSite;
	public $SensitivityMin;
	public $SensitivityMax;
	public $SensitivityComment;
	public $SpecificityMin;
	public $SpecificityMax;
	public $SpecificityComment;
	public $PPVMin;
	public $PPVMax;
	public $PPVComment;
	public $NPVMin;
	public $NPVMax;
	public $NPVComment;

	public function toAssocArray(){
		return array(
			"BiomarkerID" => $this->BiomarkerID,
			"OrganSite" => $this->OrganSite,
			"SensitivityMin" => $this->SensitivityMin,
			"SensitivityMax" => $this->SensitivityMax,
			"SensitivityComment" => $this->SensitivityComment,
			"SpecificityMin" => $this->SpecificityMin,
			"SpecificityMax" => $this->SpecificityMax,
			"SpecificityComment" => $this->SpecificityComment,
			"PPVMin" => $this->PPVMin,
			"PPVMax" => $this->PPVMax,
			"PPVComment" => $this->PPVComment,
			"NPVMin" => $this->NPVMin,
			"NPVMax" => $this->NPVMax,
			"NPVComment" => $this->NPVComment,
		);
	}
}

class dao_biomarker_organ {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("BiomarkerID","OrganSite");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `biomarker_organ` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchbiomarker_organException("No biomarker_organ found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_organ();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `biomarker_organ` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_organ();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `biomarker_organ` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_biomarker_organ();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `biomarker_organ`"; 
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
		$q = "DELETE FROM `biomarker_organ` WHERE BiomarkerID=\"$vo->BiomarkerID\" AND OrganSite=\"$vo->OrganSite\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->BiomarkerID=0;
		$vo->OrganSite=0;
	}

	public function update(&$vo){
		$q = "UPDATE `biomarker_organ` SET ";
		$q .= "BiomarkerID=\"$vo->BiomarkerID\"" . ", ";
		$q .= "OrganSite=\"$vo->OrganSite\"" . ", ";
		$q .= "SensitivityMin=\"$vo->SensitivityMin\"" . ", ";
		$q .= "SensitivityMax=\"$vo->SensitivityMax\"" . ", ";
		$q .= "SensitivityComment=\"$vo->SensitivityComment\"" . ", ";
		$q .= "SpecificityMin=\"$vo->SpecificityMin\"" . ", ";
		$q .= "SpecificityMax=\"$vo->SpecificityMax\"" . ", ";
		$q .= "SpecificityComment=\"$vo->SpecificityComment\"" . ", ";
		$q .= "PPVMin=\"$vo->PPVMin\"" . ", ";
		$q .= "PPVMax=\"$vo->PPVMax\"" . ", ";
		$q .= "PPVComment=\"$vo->PPVComment\"" . ", ";
		$q .= "NPVMin=\"$vo->NPVMin\"" . ", ";
		$q .= "NPVMax=\"$vo->NPVMax\"" . ", ";
		$q .= "NPVComment=\"$vo->NPVComment\" ";
		$q .= "WHERE BiomarkerID=\"$vo->BiomarkerID\" AND OrganSite=\"$vo->OrganSite\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `biomarker_organ` "; 
		$q .= 'VALUES("'.$vo->BiomarkerID.'","'.$vo->OrganSite.'","'.$vo->SensitivityMin.'","'.$vo->SensitivityMax.'","'.$vo->SensitivityComment.'","'.$vo->SpecificityMin.'","'.$vo->SpecificityMax.'","'.$vo->SpecificityComment.'","'.$vo->PPVMin.'","'.$vo->PPVMax.'","'.$vo->PPVComment.'","'.$vo->NPVMin.'","'.$vo->NPVMax.'","'.$vo->NPVComment.'" ) ';
		$r = $this->conn->safeQuery($q);
	}

	public function getFromResult(&$vo,$result){
		$vo->BiomarkerID = $result['BiomarkerID'];
		$vo->OrganSite = $result['OrganSite'];
		$vo->SensitivityMin = $result['SensitivityMin'];
		$vo->SensitivityMax = $result['SensitivityMax'];
		$vo->SensitivityComment = $result['SensitivityComment'];
		$vo->SpecificityMin = $result['SpecificityMin'];
		$vo->SpecificityMax = $result['SpecificityMax'];
		$vo->SpecificityComment = $result['SpecificityComment'];
		$vo->PPVMin = $result['PPVMin'];
		$vo->PPVMax = $result['PPVMax'];
		$vo->PPVComment = $result['PPVComment'];
		$vo->NPVMin = $result['NPVMin'];
		$vo->NPVMax = $result['NPVMax'];
		$vo->NPVComment = $result['NPVComment'];
	}

}

class NoSuchbiomarker_organException extends Exception {
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