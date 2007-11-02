<?php
/*
 * Copyright 2007 Crawwler Software Development
 * http://www.crawwler.com
 * 
 * 
 * Provides authentication and authorization functionality
 * 
 */
 
require_once ("Auth.php"); 			// PEAR Auth and Auth class
require_once ("cwsp_db.class.php"); // CWSP Database class

class cwsp_auth {
  var $bEnableLogging;
  var $cryptType;
  var $dsn;
  var $table;
  var $unColumn;
  var $pwColumn;
  var $dbFields;

  var $myauth;

  function cwsp_auth($dsn = 'mysql://user:pass@server/dbname', $authOptions = array()) {

    // Assign values based on passed parameters or sensible defaults
    $this->bEnableLogging = isset($authOptions['bEnableLogging']) ? $authOptions['bEnableLogging'] : false;
    $this->cryptType = isset($authOptions['cryptType']) ? $authOptions['cryptType'] : 'md5';
    $this->dsn = $dsn;
    $this->table = isset($authOptions['table'])? $authOptions['table'] : 'users';
    $this->unColumn = isset($authOptions['unColumn'])? $authOptions['unColumn'] : 'UserName';
    $this->pwColumn = isset($authOptions['pwColumn'])? $authOptions['pwColumn'] : 'Password';
    $this->dbFields = isset($authOptions['dbFields'])? $authOptions['dbFields'] : array();

    // Create options array
    $options = array (
      'enableLogging' => $this->bEnableLogging,
      'cryptType' => $this->cryptType,
      'dsn' => $this->dsn,
      'table' => $this->table,
      'usernamecol' => $this->unColumn,
      'passwordcol' => $this->pwColumn,
      'db_fields' => $this->dbFields      
    );
    // Build and Start the Auth object
    $this->myauth = new Auth("DB", $options, "", false);
    $this->startSession();
  }

  function startSession() {
    $this->myauth->start();
  }

  function endSession() {
    return $this->myauth->logout();
  }

  function checkLoginStatus() {
    return $this->myauth->checkAuth();
  }

  function changePassword($un, $pw) {
    return $this->myauth->changePassword($un, $pw);
  }

  function setSecretAnswer($un, $answer) {
    $db = new Database();
    $answer_hash = md5(strtoupper(trim($answer)));
    $result = $db->query("UPDATE users " .
    "SET SecretAnswer=\"$answer_hash\" " .
    "WHERE UserName=\"$un\" " .
    "LIMIT 1");
    if (DB :: isError($result)) {
      die("Could not set / update Secret Answer");
    }

  }

  function checkSecretAnswer($candidate, $field, $value) {

    //check the answer
    $db = new Database();
    $result = $db->query("SELECT * " .
    "FROM users " .
    "WHERE SecretAnswer=\"$candidate\" ");
    if (DB :: isError($result)) {
      die($result->getMessage());
    }

    // In case 2 or more users have an identical SecretAnswer,
    // do a further check to make sure that the provided field/value
    // pair matches as well ie SecretAnswer & Username or SecretAnswer
    // and EmailAddress..
    while ($user = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      if ($user[$field] == $value) {
        return $user; // return the user's information
      }
    }

    return false; // no matching user found
  }
}
?>