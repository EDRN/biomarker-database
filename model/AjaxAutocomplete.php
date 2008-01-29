<?php
require_once("ModelProperties.inc.php");

	// Determine what is being searched for	
	$needle = isset($_POST['autocomplete_parameter']) 
		? $_POST['autocomplete_parameter'] 
		: '';
	$objectType = isset($_POST['objectType']) 
		? $_POST['objectType']
		: '';
	$field = isset($_POST['field'])
		? $_POST['field']
		: '';
	$display = empty($_POST['fieldList'])
		? $_POST['field']
		: $_POST['fieldList'];
	
	// Verify that all required information has been specified
	if ($needle == '' || $objectType == '' || $field == '' || $display == ''){
		echo "<ul></ul>";
		exit();
	}
	
	// Build list of attributes to display
	$displayVars = explode("|",$display);
	
	// Build a Query
	$q = "SELECT * "
		."FROM  {$_POST['objectType']} "
		."WHERE {$_POST['field']} LIKE \"%$needle%\" ";
	
	// Execute the Query
	$r = $XPress->Database->safeQuery($q);
	
	// Format the results
	echo "<ul>";
	while ($result = $r->fetchRow(DB_FETCHMODE_ASSOC)){
		$components = array();
		echo "<li id=\"{$result['objId']}\">";
		foreach ($displayVars as $var){
			$components[] = $result[$var];		
		}
		echo implode(" - ",$components);
		echo "</li>";
	}
	echo "</ul>";
?>