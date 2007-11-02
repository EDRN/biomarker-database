<?php
	require_once("definitions.inc.php");
	require_once(BMDB_ROOT."/classes/Bmdb.class.php");
	require_once(BMDB_ROOT."/cots/crawwler-xld-1.0.0/XLDF.class.php");
	
	$type 		= isset($_GET['type'])? $_GET['type'] : "";
	$id 		= isset($_GET['which'])? $_GET['which'] : "";
	$redirect 	= isset($_GET['redirect'])? $_GET['redirect'] : "";
	$name = $_GET['rname'];
	$url  = $_GET['rurl'];
	

	if ($type == "" || $id == "" || $redirect == "" || $name == "" || $url == ""){
		cwsp_messages::fatal("Missing or Incorrect GET parameters.");
	}

	
	
	require_once(BMDB_ROOT."/model/ModelProperties.inc.php");

	// Create a new resource
	$r = resource::Create($name,$url);
	
	// Create a new association between this resource and the 
	// object that requested its creation
	switch ($type){
		case "biomarker":
			$br = biomarker_resource::Create($id,$r->ID);
			break;
		case "biomarker_organ":
			list($mdata,$odata) = explode(",",$id);
			list(,$m) = explode(":",$mdata);
			list(,$o) = explode(":",$odata);
			$bor = biomarker_organ_resource::Create($m,$o,$r->ID);
			break;
		case "study":
			$sr = study_resource::Create($id,$r->ID);
			break;
		default:
			cwsp_messages::fatal("Unrecognized object type {$type}. Can not associate resource.");
			break;
	}
	
	// Redirect to the calling page
	cwsp_page::httpRedirect($redirect);
?>