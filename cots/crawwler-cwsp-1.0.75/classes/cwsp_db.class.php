<?php
/*
 * Copyright 2007 Crawwler Software Development
 * http://www.crawwler.com
 * 
 * 
 * Provides basic database connectivity
 * 
 */

require_once ("DB.php"); // PEAR DB class
require_once ("cwsp_messages.class.php"); // CWSP Messages class

class cwsp_db {
  var $dsn; // The data source name
  var $conn;

  function cwsp_db($dsn = "mysql://user:pass@server/dbname") {
    $this->dsn = $dsn;
    $this->getConnection();

  }

  function getConnection() {
    $ttdb = & DB :: connect($this->dsn);
    if (PEAR :: isError($ttdb)) {
      throw new cwsp_ConnectionException($ttdb->getMessage());
    }
    $this->conn = $ttdb; 	// Set the internal variable
    return $ttdb; 			// Return the connection handle
  }

  function query($qstr) {
    $result =  $this->conn->query($qstr);
    if (DB::isError($result)){
    	throw new cwsp_QueryException($result->getMessage());
    }
    return $result;
  }

  function getOne($qstr) {
  	$result = $this->conn->getOne($qstr);
  	if (DB::isError($result)){
  		throw new cwsp_QueryException($result->getMessage());
  	}
  	return $result;
  }

  function safeQuery($qstr) {
  	$result = null;
  	try {
  		$result = self::query($qstr);  		
  	} catch (cwsp_QueryException $e) {
  		cwsp_messages::err($e->__toString());
  		if (DEBUGGING) {echo "[Last Query was: {$this->conn->last_query} ]<br/>";}
  		exit();
  	}
  	return $result;
  }

  function safeGetOne($qstr) {
  	$result = null;
  	try {
  		$result = self::getOne($qstr);
  	} catch (cwsp_QueryException $e) {
  		cwsp_messages::err($e->__toString());
  		if (DEBUGGING) {echo "[Last Query was: {$this->conn->last_query} ]<br/>";}
  		exit();
  	}
  	return $result;
  }
}

class cwsp_ConnectionException extends Exception {
	
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

class cwsp_QueryException extends Exception {
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