<?php
require_once("../../util/definitions.inc.php");
require_once(BMDB_ROOT."/classes/Bmdb.class.php");
require_once("ModelGenerator.class.php");

	$table = isset($_GET['table']) 
		? $_GET['table']
		: "Marker";
		
	$idx = isset($_GET['idx'])
		? $_GET['idx']
		: "ID";
		
	$indices = explode(":",$idx);

	$db = new cwsp_db(BMDB_DSN);
	$mg = new ModelGenerator(&$db,$table,$indices);

	echo $mg->toString();	
?>