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

	// Determine what is being searched for	
	$needle = isset($_POST['needle']) 
		? $_POST['needle'] 
		: '';
	$objectType = isset($_POST['object']) 
		? $_POST['object']
		: '';
	$field = isset($_POST['attr'])
		? $_POST['attr']
		: '';
	
	$format = isset($_POST['format']) 
		? $_POST['format']
		: 'XHTML';
	
	// Verify that all required information has been specified
	if ($needle == '' || $objectType == '' || $field == ''){
		exit();
	}
	
	
	// Build a Query
	$q = "SELECT `objId`, `{$field}` "
		."FROM  {$objectType} "
		."WHERE `{$field}` LIKE \"%{$needle}%\" ";


	$r = $xpress->db()->getAll($q);
	$results = array();
	
	// Format the results (JSON)
	if ($format == "JSON") {
		if (sizeof($r) == 0) {echo "[]";exit();}
		foreach ($r as $e=>$data) { $results[] = "[{$data['objId']},\"{$data[$field]}\"]"; } // quote data
		echo "[" . implode(",",$results) . "]";
	}

	// Format the results (XHTML) 
	if ($format == "XHTML") {
		if (sizeof($r) == 0) {echo "<ul></ul>";exit();}
		foreach ($r as $e=>$data) { $results[] = "<li><span id=\"{$data['objId']}\">{$data[$field]}</span></li>";}
		echo implode("\r\n",$results);
	}

	
	exit();
?>