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

// Singleton XPress container class:
class XPress {
	private static $instance;
	private $Database = false;
	private $Identity = false;
	
	// Defined Object Fetch Styles
	const FETCH_NONE  = "none";
	const FETCH_LOCAL = "local";
	
	private function __construct() {
		if (App::USE_DATABASE) {
			$this->Database =  new XPressDB(App::DSN);
		}
		if (App::USE_IDENTITIES) {
			$this->Identity = new XPressIdentityObject();
		}
	}
	
	public static function getInstance() {
		if (! isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	
	public function getDatabase() {
		return $this->Database;
	}
	
	public function db() {
		return $this->Database;
	}
	
	public function getIdentity() {
		return $this->Identity;
	}
	
	public function id() {
		return $this->Identity;
	}
	
	
}



?>