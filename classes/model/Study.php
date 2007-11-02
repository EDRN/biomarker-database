<?php 
/* -- Auto-generated on 2007-08-23T16:04:35-07:00 America/Los_Angeles -- */

class vo_Study {
	public $ID;
	public $EDRNID;
	public $Title;
	public $Abstract;
	public $BiomarkerPopulationCharacteristics;
	public $BiomarkerStudyType;

	public function toAssocArray(){
		return array(
			"ID" => $this->ID,
			"EDRNID" => $this->EDRNID,
			"Title" => $this->Title,
			"Abstract" => $this->Abstract,
			"BiomarkerPopulationCharacteristics" => $this->BiomarkerPopulationCharacteristics,
			"BiomarkerStudyType" => $this->BiomarkerStudyType,
		);
	}
}

class dao_Study {
	private $conn;
	private $idx;

	public function __construct(&$conn){
		$this->conn = &$conn;
		$this->idx = array("ID");	}

	public function getBy($attr,$val){
		$q = "SELECT * FROM `Study` WHERE $attr=\"$val\" ";
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchStudyException("No Study found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Study` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `Study` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Study`"; 
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
		$q = "DELETE FROM `Study` WHERE ID=\"$vo->ID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `Study` SET ";
		$q .= "ID=$vo->ID" . ", ";
		$q .= "EDRNID=$vo->EDRNID" . ", ";
		$q .= "Title=$vo->Title" . ", ";
		$q .= "Abstract=$vo->Abstract" . ", ";
		$q .= "BiomarkerPopulationCharacteristics=$vo->BiomarkerPopulationCharacteristics" . ", ";
		$q .= "BiomarkerStudyType=$vo->BiomarkerStudyType ";
		$q .= "WHERE ID=\"$vo->ID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Study` "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->EDRNID.'","'.$vo->Title.'","'.$vo->Abstract.'","'.$vo->BiomarkerPopulationCharacteristics.'","'.$vo->BiomarkerStudyType.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Study`");
	}

	public function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->EDRNID = $result['EDRNID'];
		$vo->Title = $result['Title'];
		$vo->Abstract = $result['Abstract'];
		$vo->BiomarkerPopulationCharacteristics = $result['BiomarkerPopulationCharacteristics'];
		$vo->BiomarkerStudyType = $result['BiomarkerStudyType'];
	}

}

class NoSuchStudyException extends Exception {
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