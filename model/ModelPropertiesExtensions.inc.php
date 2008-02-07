<?php
	/**
	 * This file is not autogenerated by the framework.
	 */

	// LDAP Configuration Variables
	//-----------------------------
	define("MY_LDAP_MGR_URL","http://localhost/edrn_bmdb6/webapp/ldap/lmgr.php");
	define("MY_LDAP_SERVER","ldaps://ldap.jpl.nasa.gov");
	define("MY_LDAP_SEARCHBASE","ou=personnel,dc=dir,dc=jpl,dc=nasa,dc=gov");
	define("MY_LDAP_GRACE",600);	// 10 minutes in seconds
	define('PAGEGROUP','cn=oodt,ou=personnel,dc=dir,dc=jpl,dc=nasa,dc=gov');
	
	session_start();
?>