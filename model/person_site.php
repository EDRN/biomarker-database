<?php
class person_site {

	public static function Create($PersonID,$SiteID) {
		$vo = new vo_person_site();
		$vo->PersonID = $PersonID;
		$vo->SiteID = $SiteID;
		$dao = new dao_person_site();
		$dao->save(&$vo);
		$obj = new person_site_object();
		$obj->PersonID = $vo->PersonID;
		$obj->SiteID = $vo->SiteID;
		$obj->Title = $vo->Title;
		$obj->Specialty = $vo->Specialty;
		$obj->Phone = $vo->Phone;
		$obj->Fax = $vo->Fax;
		$obj->Email = $vo->Email;
		return $obj;
	}
	public static function Retrieve($arrKeys,$arrValues,$parentObjects,$lazyFetch=false,$limit = 0) {
		$dao = new dao_person_site();
		try{
			$results = $dao->getBy($arrKeys,$arrValues);
		} catch (NoSuchperson_siteException $e){
			// No results matched the query -- silently ignore, for now
		}
		// Handle case: multiple results returned
		if (sizeof($results) > 0){
			$objs = array();
			foreach ($results as $r){
				$o = new person_site_object();
				$o->PersonID = $r->PersonID;
				$o->SiteID = $r->SiteID;
				$o->Title = $r->Title;
				$o->Specialty = $r->Specialty;
				$o->Phone = $r->Phone;
				$o->Fax = $r->Fax;
				$o->Email = $r->Email;
				//echo "-- About to check equals with parentObject->cr_object_type = $parentObject->cr_object_type<br/>";print_r($parentObject);
				if ($o->equals($parentObjects)){
					$o->person = '';
					$o->site = '';
					$o = null; // This object is a dup of its ancestors, don't add it to any results
				} else {
					if ($lazyFetch == true){
						$o->person = '';
						$o->site = '';
					} else {
						$po = $parentObjects;
						$po[] = &$o;
						try{
							$o->person = person::Retrieve(array("ID"),array("$o->PersonID"),$po,$lazyFetch,1);
							if ($o->person == null){$o->person = '';}
						} catch (NoSuchpersonException $e){
							// No results matched the query -- silently ignore
							$o->person = '';
						}
						try{
							$o->site = site::Retrieve(array("ID"),array("$o->SiteID"),$po,$lazyFetch,1);
							if ($o->site == null){$o->site = '';}
						} catch (NoSuchsiteException $e){
							// No results matched the query -- silently ignore
							$o->site = '';
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
		$dao = new dao_person_site();
		$dao->delete(&$vo);
	}
	public static function Save(&$objInstance) {
		$vo  = $objInstance->getVO();
		$dao = new dao_person_site;
		$dao->save(&$vo);
	}
}

class person_site_object {

	public $cr_object_type = "person_site";
	public $PersonID = '';
	public $SiteID = '';
	public $Title = '';
	public $Specialty = '';
	public $Phone = '';
	public $Fax = '';
	public $Email = '';
	public $person = '';
	public $site = '';

	public function __construct() {
		//echo "creating object of type $this->cr_object_type<br/>";
		$this->cr_object_type = "person_site";
	}

	// Accessor Functions (get)
	public function get_PersonID() {
		 return $this->PersonID;
	}
	public function get_SiteID() {
		 return $this->SiteID;
	}
	public function get_Title() {
		 return $this->Title;
	}
	public function get_Specialty() {
		 return $this->Specialty;
	}
	public function get_Phone() {
		 return $this->Phone;
	}
	public function get_Fax() {
		 return $this->Fax;
	}
	public function get_Email() {
		 return $this->Email;
	}

	// Mutator Functions (set)
	public function set_PersonID($value) {
		$this->PersonID = $value;
	}
	public function set_SiteID($value) {
		$this->SiteID = $value;
	}
	public function set_Title($value) {
		$this->Title = $value;
	}
	public function set_Specialty($value) {
		$this->Specialty = $value;
	}
	public function set_Phone($value) {
		$this->Phone = $value;
	}
	public function set_Fax($value) {
		$this->Fax = $value;
	}
	public function set_Email($value) {
		$this->Email = $value;
	}


	public function equals($objArray){
		if ($objArray == null){return false;}
		foreach ($objArray as $obj){
			//echo "::EQUALS:: comparing $this->cr_object_type WITH $obj->cr_object_type &nbsp;";
			// Check object types first
			if ($this->cr_object_type == $obj->cr_object_type){
				// Check each primary key next
				if ($this->PersonID != $obj->PersonID){continue;}
				if ($this->SiteID != $obj->SiteID){continue;}
				return true;
			}
		}
		return false;
	}
	public function getVO() {
		$vo = new vo_person_site();
		$vo->PersonID = $this->PersonID;
		$vo->SiteID = $this->SiteID;
		$vo->Title = $this->Title;
		$vo->Specialty = $this->Specialty;
		$vo->Phone = $this->Phone;
		$vo->Fax = $this->Fax;
		$vo->Email = $this->Email;
		return $vo;
	}
	public function toRDF($namespace,$urlBase) {
		return "";
	}
	public function toRDFStub($namespace,$urlBase) {
		return "";
	}
}

class vo_person_site {
	public $PersonID;
	public $SiteID;
	public $Title;
	public $Specialty;
	public $Phone;
	public $Fax;
	public $Email;

	public function toAssocArray(){
		return array(
			"PersonID" => $this->PersonID,
			"SiteID" => $this->SiteID,
			"Title" => $this->Title,
			"Specialty" => $this->Specialty,
			"Phone" => $this->Phone,
			"Fax" => $this->Fax,
			"Email" => $this->Email,
		);
	}
}

class dao_person_site {
	private $conn;
	private $idx;

	public function __construct(){
		$this->conn = new cwsp_db(Modeler::DSN);
		$this->idx = array("PersonID","SiteID");
	}

	public function getBy($attrs,$vals){
		if (sizeof($attrs) == 0 || sizeof($vals) == 0){ return array(); }
		if (sizeof($attrs) != sizeof($vals)){ return array(); }
		$q = "SELECT * FROM `person_site` WHERE ";
		for ($i=0;$i<sizeof($attrs)-1;$i++){
			$q .= " {$attrs[$i]}=\"{$vals[$i]}\" AND ";
		}
		if (sizeof($attrs)-1 >= 0){
			$q .= " {$attrs[sizeof($attrs)-1]}=\"{$vals[sizeof($attrs)-1]}\" ";
		}
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchperson_siteException("No person_site found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_person_site();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `person_site` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_person_site();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `person_site` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_person_site();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `person_site`"; 
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
		$q = "DELETE FROM `person_site` WHERE PersonID=\"$vo->PersonID\" AND SiteID=\"$vo->SiteID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->PersonID=0;
		$vo->SiteID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `person_site` SET ";
		$q .= "PersonID=\"$vo->PersonID\"" . ", ";
		$q .= "SiteID=\"$vo->SiteID\"" . ", ";
		$q .= "Title=\"$vo->Title\"" . ", ";
		$q .= "Specialty=\"$vo->Specialty\"" . ", ";
		$q .= "Phone=\"$vo->Phone\"" . ", ";
		$q .= "Fax=\"$vo->Fax\"" . ", ";
		$q .= "Email=\"$vo->Email\" ";
		$q .= "WHERE PersonID=\"$vo->PersonID\" AND SiteID=\"$vo->SiteID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `person_site` "; 
		$q .= 'VALUES("'.$vo->PersonID.'","'.$vo->SiteID.'","'.$vo->Title.'","'.$vo->Specialty.'","'.$vo->Phone.'","'.$vo->Fax.'","'.$vo->Email.'" ) ';
		$r = $this->conn->safeQuery($q);
	}

	public function getFromResult(&$vo,$result){
		$vo->PersonID = $result['PersonID'];
		$vo->SiteID = $result['SiteID'];
		$vo->Title = $result['Title'];
		$vo->Specialty = $result['Specialty'];
		$vo->Phone = $result['Phone'];
		$vo->Fax = $result['Fax'];
		$vo->Email = $result['Email'];
	}

}

class NoSuchperson_siteException extends Exception {
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