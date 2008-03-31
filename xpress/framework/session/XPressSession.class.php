<?php
/*
 * Copyright 2007-2008 Crawwler Software Development
 * http://www.crawwler.com
 * 
 * Project: crawwler-xpress (XPress)
 * File: XPressSession.class.php
 * Author: andrew (andrew@crawwler.com)
 * Date: 23.Mar.2008
 * 
 * Lineage: cwsp_session.class.php
 * Date: 30.May.2007
 * 
 * 
 * Provides basic session management functionality
 *  
 */

class XPressSession{
  
  public static function init(){
    session_start();
    header("Cache-control:private");
  }
  
  public static function test($attr) {
    $s =& $_SESSION;
    if (is_array($attr)) {
	  	foreach ($attr as $p){
	  		if (isset($s[$p])){
	  			$s =& $s[$p];
	  		} else {
	  			return false;
	  		}
	  	}
	  	if (isset($s) && !empty($s)) {
	  		return true;
	  	} else {
	  		return false;
	  	}
    } else {
    	if (isset($s[$attr]) && !empty($s[$attr])) {
    		return true;
    	} else {
    		return false;
    	}
    }
  }
  
  public static function get($attr) {
  	$s =& $_SESSION;
  	if (is_array($attr)) {
	  	foreach ($attr as $p){
	  		if (isset($s[$p])){
	  			$s =& $s[$p];
	  		} else {
	  			return false;
	  		}
	  	}
	  	return $s;
  	} else {
  		if (isset($s[$attr])) {
  			return $s[$attr];
  		} else {
  			return false;
  		}
  	}
  }

  public static function set($attr,$value) {
  	$s =& $_SESSION;
  	if (is_array($attr)) {
	  	foreach ($attr as $p) {
	  		if (isset($s[$p])) {
	  			$s =& $s[$p];
	  		} else {
	  			$s[$p] = array();
	  			$s =& $s[$p];
	  		}
	  	}
  		$s = $value;
  	} else {
  		$s[$attr] = $value;
  	}
  }
  
  public static function clear($attr) {
  	$s =& $_SESSION;
  	if (is_array($attr)) {
  		$last = '';
  		for($i=0;$i<sizeof($attr)-1;$i++){
	  		if (isset($s[$attr[$i]])) {
	  			$s =& $s[$attr[$i]];
	  		} else {
	  			return;	// attr doesn't exist
	  		}
	  	}
	  	unset($s[$attr[sizeof($attr)-1]]);
  	} else {
  		if (isset($s[$attr])) {
  			unset($s[$attr]);
  		}
  	}
  }
  
  public static function destroy(){
    session_destroy();
  }
  
  public static function spew(){
  	var_dump($_SESSION);
  }
  
}

?>