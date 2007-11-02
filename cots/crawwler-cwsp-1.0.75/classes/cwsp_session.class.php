<?php
/*
 * Copyright 2007 Crawwler Software Development
 * http://www.crawwler.com
 * 
 * 
 * Provides basic session management functionality
 *  
 */

class cwsp_session {
  
  function cwsp_session(){
    
    
  }
  
  function init(){
    session_start();
    header("Cache-control:private");
  }
  
  function get($attr,$attrPrefix = ''){
  	if ($attrPrefix != ''){
	  	if (isset($_SESSION[$attrPrefix][$attr])){
	  		return $_SESSION[$attrPrefix][$attr];
	  	} else {
	  		return '';
	  	}  	
    } else {
    	if (isset($_SESSION[$attr])){
	  		return $_SESSION[$attr];
	  	} else {
	  		return '';
	  	}     	
    }  	
  }
  
  function set($attr,$value,$attrPrefix = '') {
  	if ($attrPrefix != ''){
  		$_SESSION[$attrPrefix][$attr] = $value;
  	} else {
  		$_SESSION[$attr] = $value;
  	}
  }
  
  function clear($attr,$attrPrefix) {
  	if ($attrPrefix != ''){
  		$_SESSION[$attrPrefix][$attr] = '';
  	} else {
  		$_SESSION[$attr] = '';
  	}
  }
  
  function destroy(){
    session_destroy();
  }
  
  function spew(){
  	print_r($_SESSION);
  }
  
}

?>