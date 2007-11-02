<?php 
/* -- Auto-generated on 2007-08-23T16:50:48-07:00 America/Los_Angeles -- */

class vo_Biomarker {
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

class dao_Biomarker {
	private $conn;
	private $idx;

	public function __construct(&$conn){
		$this->conn = &$conn;
		$this->idx = array("ID");	}

	public function getBy($attr,$val){
		$q = "SELECT * FROM `Biomarker` WHERE $attr=\"$val\" ";
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchBiomarkerException("No Biomarker found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Biomarker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Biomarker` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Biomarker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		if (sizeof($ids) == 0) { return array();}
		$q = "SELECT * FROM `Biomarker` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn->safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Biomarker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Biomarker`"; 
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
		$q = "DELETE FROM `Biomarker` WHERE ID=\"$vo->ID\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->ID=0;
	}

	public function update(&$vo){
		$q = "UPDATE `Biomarker` SET ";
		$q .= "ID=$vo->ID" . ", ";
		$q .= "EKE_ID=$vo->EKE_ID" . ", ";
		$q .= "BiomarkerID=$vo->BiomarkerID" . ", ";
		$q .= "PanelID=$vo->PanelID" . ", ";
		$q .= "Title=$vo->Title" . ", ";
		$q .= "Description=$vo->Description" . ", ";
		$q .= "QAState=$vo->QAState" . ", ";
		$q .= "Phase=$vo->Phase" . ", ";
		$q .= "Security=$vo->Security" . ", ";
		$q .= "Type=$vo->Type ";
		$q .= "WHERE ID=\"$vo->ID\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Biomarker` "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->EKE_ID.'","'.$vo->BiomarkerID.'","'.$vo->PanelID.'","'.$vo->Title.'","'.$vo->Description.'","'.$vo->QAState.'","'.$vo->Phase.'","'.$vo->Security.'","'.$vo->Type.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Biomarker`");
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

class NoSuchBiomarkerException extends Exception {
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