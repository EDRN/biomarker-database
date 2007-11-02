<?php
class vo_user {
	
	public $ID;
	public $FirstName;
	public $LastName;
	public $Email;
	public $Password;
	public $Role;
	
	public function toAssocArray(){
		return array("ID" => $this->ID,
					"FirstName" => $this->FirstName,
					"LastName" => $this->LastName,
					"Email" => $this->Email,
					"Password" => $this->Password,
					"Role" => $this->Role);
	}
}

class dao_user {
	private $conn;
	
	public function __construct(&$conn){
		$this->conn = &$conn;
	}
	
	public function getBy($attr,$val){
		$q = "SELECT * FROM users WHERE $attr=\"$val\" ";
		$r = $this->conn->safeQuery($q);
		if ($r->numRows() == 0){
			throw new NoSuchUserException("No user found with $attr = $id.");
		}
		$vo = new vo_user();
		$this->getFromResult(&$vo,$r->fetchRow(DB_FETCHMODE_ASSOC));
		return($vo);
	}
	
	public function save(&$vo){
		if ($vo->ID == 0) {
			$this->insert($vo);
		} else {
			$this->update($vo);
		}
	}
	
	public function delete(&$vo){
		$q = "DELETE FROM users WHERE ID=" . $vo->id;
		$this->conn->safeQuery($q);	// delete from the database
		$vo->id = 0;				// update the vo to reflect deletion
		
	}
	
	private function update(&$vo)
	{	
		$q  = "UPDATE users SET ";
		$q .= "ID=$vo->ID" . ", ";
		$q .= "FirstName=\"$vo->FirstName\" " . ", ";
		$q .= "LastName=\"$vo->LastName\" " . ", ";
		$q .= "Email=\"$vo->Email\" " . ", ";
		$q .= "Password=\"$vo->Password\" " . ", ";
		$q .= "Role=$vo->Role ";
		
		$q .= "WHERE ID=$vo->ID ";
		$r = $this->conn->safeQuery($q);
	}
	
	private function insert(&$vo){
		// insert this vo into the database as a new row
		$q  = "INSERT INTO users ";
		$q .= "VALUES(\"\",\"$vo->FirstName\",\"$vo->LastName\",\"$vo->Email\",\"$vo->Password\",\"$vo->Role\") ";
		$r  = $this->conn->safeQuery($q);
		$vo->ID = $this->conn->safeGetOne("SELECT LAST_INSERT_ID() FROM users");
	}
	
	private function getFromResult(&$vo,$result){
		$vo->ID = $result['ID'];
		$vo->FirstName 	= $result['FirstName'];
		$vo->LastName  	= $result['LastName'];
		$vo->Email		= $result['Email'];
		$vo->Password	= $result['Password'];
		$vo->Role		= $result['Role'];
	}
}


class NoSuchUserException extends Exception {
	public function __construct($message,$code = 0){
			parent::__construct($message, $code);
		}
		
	public function __toString() {
		$str = "<strong>".__CLASS__ . " Occurred: </strong>";
		$str .=  "(Code: {$this->code}) ";
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
		foreach(self::getTrace() as $file){
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