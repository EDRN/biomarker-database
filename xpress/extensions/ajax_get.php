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
	require_once("../app.php");
	if ((!isset($_GET['otype']) || empty($_GET['otype'])) ||
		(!isset($_GET['oid']) || empty($_GET['oid']))) {
			
		echo "error";
		exit();
	}
	
	$otype = strtolower($_GET['otype']);
	$oid = $_GET['oid'];
	if ($oid <= 0) {
		echo "error";
		exit();
	}
	
	try {
		switch ($otype) {
			case 'publication':
				if (false !== ($p = PublicationFactory::Retrieve($oid))) {
					echo $p->toJSON();
				} else {echo "error"; exit();}
				break;
			default:
				echo "error";
				exit();
			
		}
	} catch (XPressException $e) {
		echo "error";
		exit();
	}	
?>