<?php
/*
 * Copyright 2007-2008 Crawwler Software Development.
 * http://www.crawwler.com
 * 
 * Project: CWSP - XPress
 * File: XPressIdentityFramework.class.php
 * Created on March 23, 2008
 * Author: andrew
 * 
 * Lineage: cwsp_auth (Crawwler Web Services Project)
 * Date: 30.May.2007
 *
 */
require_once ("Auth.php");		 // PEAR Auth class
class XPressAuthWrapper {

  private $bEnableLogging;
  private $cryptType;
  private $dsn;
  private $table;
  private $unColumn;
  private $pwColumn;
  private $dbFields;

  public $myauth;

  public function __construct($authOptions = array ()) {

    // Assign values based on passed parameters or sensible defaults
    $this->bEnableLogging = isset($authOptions['bEnableLogging']) ? $authOptions['bEnableLogging'] : false;
    $this->cryptType = isset($authOptions['cryptType']) ? $authOptions['cryptType'] : 'md5';
    $this->dsn = App::DSN;
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
    $this->myauth =& new Auth("DB", $options, "", false);
    $this->startSession();
  }

  public function startSession() {
    $this->myauth->start();
  }

  public function endSession() {
    return $this->myauth->logout();
  }

  public function checkLoginStatus() {
    return $this->myauth->checkAuth();
  }

  public function changePassword($un, $pw) {
    return $this->myauth->changePassword($un, $pw);
  }
}
?>