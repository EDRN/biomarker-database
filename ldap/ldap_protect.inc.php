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
	if (empty($_SESSION) || !isset($_SESSION['auth'])){
		header("Location: " . MY_LDAP_MGR_URL . "?r={$_SERVER['REQUEST_URI']}");
	}

?>