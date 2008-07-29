<?php
/**
 * 	EDRN Biomarker Database
 *  Curation Webapp
 * 
 *  Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 *  
 *  Copyright (c) 2008, California Institute of Technology. 
 *  ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
 *  
 */

require_once ("DB.php"); // PEAR DB class

class XPressDB {
  public $dsn; 	// The data source name
  public $conn;	// The PEAR DB connection

  public function __construct($dsn = "mysql://user:pass@server/dbname") {
    $this->dsn = $dsn;
    $this->getConnection();
  }

  public function getConnection() {
    $ttdb =& DB::connect($this->dsn);
    if (PEAR::isError($ttdb)) {
	  throw new XPressException(
	  	"XPressDB","ConnectionException".$ttdb->getMessage(),
    		"Last query was: {$qstr}");
    }
    $this->conn = $ttdb; // Set the internal variable
    return $this->conn;  // Return the connection handle
  }

  public function query($qstr) {
  	$result =  $this->conn->query($qstr);
    if (DB::isError($result)){
    	throw new XPressException(
    		"XPressDB","QueryException",$result->getMessage(),
    		"Last query was: {$qstr}");
    }
    return $result;
  }

  public function getOne($qstr) {
  	$result = $this->conn->getOne($qstr);
  	if (DB::isError($result)){
  		throw new XPressException(
    		"XPressDB","QueryException",$result->getMessage(),
    		"Last query was: {$qstr}");
  	}
  	return $result;
  }
  
  public function getRow($qstr, $mode = DB_FETCHMODE_ASSOC) {
  	$result = $this->conn->getRow($qstr,$mode);
  	if (DB::isError($result)){
  		throw new XPressException(
    		"XPressDB","QueryException",$result->getMessage(),
    		"Last query was: {$qstr}");
  	}
  	return $result;
  }
  
  public function getAll($qstr, $mode = DB_FETCHMODE_ASSOC) {
  	$result = $this->conn->getAll($qstr,$mode);
  	if (DB::isError($result)){
  		throw new XPressException(
    		"XPressDB","QueryException",$result->getMessage(),
    		"Last query was: {$qstr}");
  	}
  	return $result;	
  } 
}
?>