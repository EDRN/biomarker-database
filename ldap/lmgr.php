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
	require_once("../model/ModelProperties.inc.php");
	require_once("components/authenticate.php");
	require_once("components/authorize.php");
	
	if (isset($_GET['r']) && !empty($_GET['r'])){
		header("Location: {$_GET['r']}");
	} else {
		echo "<span>You have logged in successfully. <br/>"
			."<a href=\"../\">Click here to go home</a></span>";
	}
	
?>