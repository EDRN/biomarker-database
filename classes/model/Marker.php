<?php 
/* -- Auto-generated on 2007-08-16T08:23:18-07:00 America/Los_Angeles -- */

class vo_Marker {
	public $ID;
	public $registryName;
	public $registryURI;
	public $registryID;
	public $longName;
	public $security;
	public $type;
	public $description;
	public $markerQAState;
	public $isPanel;
	
    const idx = "ID";	// The field to use as an index

	public function toAssocArray(){
		return array(
			"ID" => $this->ID,
			"registryName" => $this->registryName,
			"registryURI" => $this->registryURI,
			"registryID" => $this->registryID,
			"longName" => $this->longName,
			"security" => $this->security,
			"type" => $this->type,
			"description" => $this->description,
			"markerQAState" => $this->markerQAState,
			"isPanel" => $this->isPanel,
		);
	}
}

class dao_Marker {
	private $conn;

	public function __construct(&$conn){
		$this->conn = &$conn;
	}

	public function getBy($attr,$val){
		$q = "SELECT * FROM Marker WHERE $attr=\"$val\" ";
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchMarkerException("No Marker found with $attr = $val");
		}
		$vo = new vo_Marker();
		$this->getFromResult(&$vo,$r->fetchRow(DB_FETCHMODE_ASSOC));
		return($vo);
	}

	public function getRange($start,$end){
		$results = array();
		$count = $end - $start;
		$q = "SELECT * FROM Marker LIMIT $start, $count"; 
		$r = $this->conn->safeQuery($q); 
		while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vo = new vo_Marker();
			$this->getFromResult(&$vo,$result);
			$results[] = $vo;
		}
		return($results);
	}

	public function numRecords(){
		$q = "SELECT COUNT(*) FROM Marker"; 
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
		$q = "DELETE FROM Marker WHERE " . self::idx . "=" . $vo->ID;
		$this->conn->safeQuery($q); // delete from the database 
		$vo->id = 0; //update the vo to reflect deletion 
	}

	public function update(&$vo){
		$q = "UPDATE markers SET ";
		$q .= "ID=$vo->ID" . ", ";
		$q .= "registryName=$vo->registryName" . ", ";
		$q .= "registryURI=$vo->registryURI" . ", ";
		$q .= "registryID=$vo->registryID" . ", ";
		$q .= "longName=$vo->longName" . ", ";
		$q .= "security=$vo->security" . ", ";
		$q .= "type=$vo->type" . ", ";
		$q .= "description=$vo->description" . ", ";
		$q .= "markerQAState=$vo->markerQAState" . ", ";
		$q .= "isPanel=$vo->isPanel ";
		$q .= "WHERE ID=$vo->ID "; 
		$r = $this->conn->safeQuery($q);
	}

	private function insert(&$vo){
		//insert this vo into the database as a new row
		$q = "INSERT INTO Marker "; 
		$q .= 'VALUES("'.$vo->ID.'","'.$vo->registryName.'","'.$vo->registryURI.'","'.$vo->registryID.'","'.$vo->longName.'","'.$vo->security.'","'.$vo->type.'","'.$vo->description.'","'.$vo->markerQAState.'","'.$vo->isPanel.'" ) ';
		$r = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM Marker");
	}

	public function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->registryName = $result['registryName'];
		$vo->registryURI = $result['registryURI'];
		$vo->registryID = $result['registryID'];
		$vo->longName = $result['longName'];
		$vo->security = $result['security'];
		$vo->type = $result['type'];
		$vo->description = $result['description'];
		$vo->markerQAState = $result['markerQAState'];
		$vo->isPanel = $result['isPanel'];
	}

}

class NoSuchMarkerException extends Exception {
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