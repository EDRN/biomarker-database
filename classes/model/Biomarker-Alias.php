<?php 
/* -- Auto-generated on 2007-08-23T10:31:55-07:00 America/Los_Angeles -- */

class vo_Biomarker_Alias {
	public $BiomarkerID;
	public $Alias;

	public function toAssocArray(){
		return array(
			"BiomarkerID" => $this->BiomarkerID,
			"Alias" => $this->Alias,
		);
	}
}

class dao_Biomarker_Alias {
	private $conn;
	private $idx;

	public function __construct(&$conn){
		$this->conn = &$conn;
		$this->idx = array("BiomarkerID","Alias");	}

	public function getBy($attr,$val){
		$q = "SELECT * FROM `Biomarker-Alias` WHERE $attr=\"$val\" ";
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchBiomarker_AliasException("No Biomarker-Alias found with $attr = $val");
		}
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Biomarker_Alias();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM `Biomarker-Alias` LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Biomarker_Alias();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function getSubset($field,$ids){
		$q = "SELECT * FROM `Biomarker-Alias` WHERE $field IN (";
		for($i=0;$i<sizeof($ids) - 1;$i++){
			$q .= $ids[$i] . ",";
		}
		if (sizeof($ids) > 0){
			$q .= $ids[sizeof($ids)-1];
		}
		$q .= ")";
		$r = $this->conn-safeQuery($q);
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Biomarker_Alias();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM `Biomarker-Alias`"; 
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
		$q = "DELETE FROM `Biomarker-Alias` WHERE BiomarkerID=\"$vo->BiomarkerID\" AND Alias=\"$vo->Alias\" ";
		$this->conn->safeQuery($q); // delete from the database 
		$vo->BiomarkerID=0;
		$vo->Alias=0;
	}

	public function update(&$vo){
		$q = "UPDATE `Biomarker-Alias` SET ";
		$q .= "BiomarkerID=$vo->BiomarkerID" . ", ";
		$q .= "Alias=$vo->Alias ";
		$q .= "WHERE BiomarkerID=\"$vo->BiomarkerID\" AND Alias=\"$vo->Alias\" ";
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO `Biomarker-Alias` "; 
		$q .= 'VALUES("'.$vo->BiomarkerID.'","'.$vo->Alias.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM `Biomarker-Alias`");
	}

	public function getFromResult(&$vo,$result){
		$vo->BiomarkerID = $result['BiomarkerID'];
		$vo->Alias = $result['Alias'];
	}

}

class NoSuchBiomarker_AliasException extends Exception {
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