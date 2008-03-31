<?php
/*
 * Copyright 2007-2008 Crawwler Software Development
 * http://www.crawwler.com
 * 
 * Project: crawwler-xpress (XPress)
 * File: XPressDB.class.php
 * Author: andrew (andrew@crawwler.com)
 * Date: 23.Mar.2008
 * 
 * Lineage: cwsp_db.class.php (Crawwler Web Services Project)
 * Date: 30.May.2007
 * 
 * 
 * Provides basic database connectivity
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