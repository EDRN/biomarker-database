<?php 
/* -- Auto-generated on 2007-08-09T10:54:45-07:00 America/Los_Angeles -- */

class vo_Publications {
	public $ID;
	public $pubMedID;
	public $authors;
	public $title;
	public $journal;
	public $volume;
	public $issue;
	public $year;

	public function toAssocArray(){
		return array(
			"ID" => $this->ID,
			"pubMedID" => $this->pubMedID,
			"authors" => $this->authors,
			"title" => $this->title,
			"journal" => $this->journal,
			"volume" => $this->volume,
			"issue" => $this->issue,
			"year" => $this->year,
		);
	}
}

class dao_Publications {
	private $conn;

	public function __construct(&$conn){
		$this->conn = &$conn;
	}

	public function getBy($attr,$val){
		$q = "SELECT * FROM Publications WHERE $attr=\"$val\" ";
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchPublicationsException("No Publications found with $attr = $val");
		}
		$vo = new vo_Publications();
		$this->getFromResult(&$vo,$r->fetchRow(DB_FETCHMODE_ASSOC));
		return($vo);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM Publications LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Publications();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM Publications"; 
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
		$q = "DELETE FROM Publications WHERE ID=" . $vo->ID;
		$this->conn->safeQuery($q); // delete from the database 
		$vo->id = 0; //update the vo to reflect deletion 
	}

	public function update(&$vo){
		$q = "UPDATE markers SET ";
		$q .= "ID=$vo->ID" . ", ";
		$q .= "pubMedID=$vo->pubMedID" . ", ";
		$q .= "authors=$vo->authors" . ", ";
		$q .= "title=$vo->title" . ", ";
		$q .= "journal=$vo->journal" . ", ";
		$q .= "volume=$vo->volume" . ", ";
		$q .= "issue=$vo->issue" . ", ";
		$q .= "year=$vo->year ";
		$q .= "WHERE ID=$vo->ID "; 
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO Publications "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->pubMedID.'","'.$vo->authors.'","'.$vo->title.'","'.$vo->journal.'","'.$vo->volume.'","'.$vo->issue.'","'.$vo->year.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM Publications");
	}

	public function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->pubMedID = $result['pubMedID'];
		$vo->authors = $result['authors'];
		$vo->title = $result['title'];
		$vo->journal = $result['journal'];
		$vo->volume = $result['volume'];
		$vo->issue = $result['issue'];
		$vo->year = $result['year'];
	}

}

class NoSuchPublicationsException extends Exception {
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