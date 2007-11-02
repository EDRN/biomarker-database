<?php
/**
 * EDRN Biomarker Database 
 * 
 * Author: ahart (andrew.f.hart@jpl.nasa.gov)
 * Date: 24.July.2007
 *
 *
 * This class describes users of the biomarker database webapp
 *
**/

require_once("cots/crawwler-cwsp-1.0.75/cwsp.inc.php");

class User {
	
	public $id;			// The unique ID of the user
	public $firstName;	// The user's first (given) name
	public $lastName;	// The user's last name (surname)
	public $email;		// The user's email address (also acts as username)
	public $role;		// the user's role
	
	private $db;		// cwsp_db object to handle all database requests
	private $auth;		// cwsp_auth object to handle authentication aspects
	
	
	public function __construct($data = array()){
		if (isset($data['ID'])){ $this->id = $data['ID'];}
		if (isset($data['FirstName'])){ $this->firstName = $data['FirstName'];}
		if (isset($data['LastName'])){ $this->lastName = $data['LastName'];}
		if (isset($data['Email'])){ $this->email = $data['Email'];}
		if (isset($data['Role'])){ $this->role = $data['Role'];}
		
		try {
		$this->db = new cwsp_db(BMDB_DSN);
		} catch (cwsp_ConnectionException $e){
			cwsp_messages::fatal($e->__toString());
		}
		$this->auth = new cwsp_auth(BMDB_DSN,array("dbFields" => array("ID"),"unColumn" =>"Email"));
	}
	
	public function exists($id,$db_conn = null){
		$db = $db_conn;
		// If we have not been provided with an open database connection
		if ($db == null){
			// Create our own database connection to handle this request
			try {
				$db = new cwsp_db(BMDB_DSN);	
			} catch (cwsp_ConnectionException $e) {
				echo $e->__toString() .  "<br/>";
				exit();
			}
		}
		
		// if a user with the provided ID exists, return a new User object 
		// comprising the user's info
		$q = "SELECT * FROM users WHERE ID=$id";
		$result = $db->safeQuery($q);
		if ($result == null){exit(); /* Theres been a database error */}
		if ($result->numRows() == 0){
			return false;
		} else {
			// Create an array with this user's info
			$data = $result->fetchRow(DB_FETCHMODE_ASSOC);
			return new User($data);
		}			
	}
	/*
	private function getInfo(){
		echo "getInfo called";
		die();
		$q = "SELECT * FROM users WHERE ID=".$this->id;
		try {
			$result = $this->db->query($q);
			if ($result->numRows() == 0){
				throw new NoSuchUserException("No user exists with ID = $this->id");
			} else {
				$data = $result->fetchRow(DB_FETCHMODE_ASSOC);
				if (isset($data['ID'])){$this->id = $data['ID'];} else {throw new MissingUserDataException("ID missing");}
				if (isset($data['FirstName'])){$this->firstName = $data['FirstName'];} else {throw new MissingUserDataException("FirstName missing");}
				if (isset($data['LastName'])){$this->lastName = $data['LastName'];} else {throw new MissingUserDataException("LastName missing");}
				if (isset($data['Email'])){$this->email = $data['Email'];} else {throw new MissingUserDataException("Email missing");}
				if (isset($data['Role'])){$this->role = $data['Role'];} else {throw new MissingUserDataException("Role missing");}
			}
		} catch (cwsp_QueryException $qe){
			cwsp_messages::err($qe->__toString());
			exit();		
		}		
	}
*/
	
	private function setSession($prefix = ''){
		$userdata = array(
			"ID" => $this->id,
			"FirstName" => $this->firstName,
			"LastName" => $this->lastName,
			"Email" => $this->email,
			"Role" => $this->role);
		cwsp_session::set("User",$userdata,$prefix);
		echo " user.class.php:97 -- JUST SET THE SESSION: ";
		cwsp_session::spew();	
		die();	
	}
	private function unsetSession($prefix = ''){
		cwsp_session::clear("User",$prefix);
	}
/*
	public function logIn($sessionPrefix = ''){
		die("whazzuh???");
		if ($this->auth == null){
			$this->auth = new cwsp_auth(BMDB_DSN,array("dbFields" => array("ID"),"unColumn" =>"Email"));
			$this->auth->startSession();
		}
		// isLoggedIn calls AUTH::checkLoginStatus which transparently
		// handles the login based on the presence of POST variables set 
		// as described in the cwsp_auth constructor (defined in file:
		// cwsp_auth.class.php)
		die("getting hurr");
		if ($this->isLoggedIn() ){
			$this->id = $_SESSION['_authsession']['data']['ID'];
			die("ID: " . $this->id); 
			try {
				$this->getInfo();	// Load data about ourself from the database 
				$this->setSession($sessionPrefix); 	// Persist info in the session variable	
				return true;
			} catch (MissingUserDataException $mude){
				cwsp_messages::fatal($mude->__toString());
				// fatal includes a call to die() so no return value needed
			}		
		}
		return false;			
	}
	*/
	public function logOut($sessionPrefix = ''){
		$this->auth->endSession();
		$this->unsetSession($sessionPrefix);
		cwsp_session::destroy();
	}
	
	public function isLoggedIn(){
		return $this->auth->checkLoginStatus(); 
	}
	
	
	public static function getInfo($email){
		echo "getInfo called";
		die();
		$q = "SELECT * FROM users WHERE Email=".$email;
		try {
			$result = $this->db->query($q);
			if ($result->numRows() == 0){
				throw new NoSuchUserException("No user exists with email = $email");
			} else {
				return $result->fetchRow(DB_FETCHMODE_ASSOC);
				/*
				if (isset($data['ID'])){$this->id = $data['ID'];} else {throw new MissingUserDataException("ID missing");}
				if (isset($data['FirstName'])){$this->firstName = $data['FirstName'];} else {throw new MissingUserDataException("FirstName missing");}
				if (isset($data['LastName'])){$this->lastName = $data['LastName'];} else {throw new MissingUserDataException("LastName missing");}
				if (isset($data['Email'])){$this->email = $data['Email'];} else {throw new MissingUserDataException("Email missing");}
				if (isset($data['Role'])){$this->role = $data['Role'];} else {throw new MissingUserDataException("Role missing");}
				*/
}
		} catch (cwsp_QueryException $qe){
			cwsp_messages::err($qe->__toString());
			exit();		
		}		
	}
	
	
	public static function login($username){
		
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

class MissingUserDataException extends Exception {
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