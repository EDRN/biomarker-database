<?php 
/* -- Auto-generated on 2007-08-23T10:13:29-07:00 America/Los_Angeles -- */

class vo_Biomarker_Study {
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

class dao_Biomarker_Study {
	private $conn;
	private $idx;

	public function __construct(&$conn){
		$this->conn = &$conn;
		$this->idx = array("BiomarkerID","StudyID");	}

	public function getBy($attr,$val){
		$q = "SELECT * FROM `Biomarker-Study` WHERE $attr=\"$val\" ";
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchBiomarker_StudyException("No Biomarker-Study found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Biomarker_Study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Biomarker-Study` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Biomarker_Study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		$q = "SELECT * FROM `Biomarker-Study` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn-safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Biomarker_Study();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Biomarker-Study`"; 
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
		$q = "DELETE FROM `Biomarker-Study` WHERE BiomarkerID=\"$vo->BiomarkerID\" AND StudyID=\"$vo->StudyID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->BiomarkerID=0;
		$vo->StudyID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `Biomarker-Study` SET ";
		$q .= "BiomarkerID=$vo->BiomarkerID" . ", ";
		$q .= "StudyID=$vo->StudyID" . ", ";
		$q .= "Sensitivity=$vo->Sensitivity" . ", ";
		$q .= "Specificity=$vo->Specificity" . ", ";
		$q .= "PPV=$vo->PPV" . ", ";
		$q .= "NPV=$vo->NPV" . ", ";
		$q .= "Assay=$vo->Assay" . ", ";
		$q .= "Technology=$vo->Technology ";
		$q .= "WHERE BiomarkerID=\"$vo->BiomarkerID\" AND StudyID=\"$vo->StudyID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Biomarker-Study` "; 
		$q .= 'VALUES("'.$vo->BiomarkerID.'","'.$vo->StudyID.'","'.$vo->Sensitivity.'","'.$vo->Specificity.'","'.$vo->PPV.'","'.$vo->NPV.'","'.$vo->Assay.'","'.$vo->Technology.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Biomarker-Study`");
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

class NoSuchBiomarker_StudyException extends Exception {
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