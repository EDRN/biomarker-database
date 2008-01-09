<?php
	/* Resource Management */
	if ($_POST['action'] == 'createAndAssociateResource'){
			// Get post variables
			$url  = "http://".$_POST['resUrl'];
			$desc = $_POST['resDesc'];
			$objType = $_POST['objType'];
			$objId   = $_POST['objId'];
			
			// Build the object itself 
			$obj = new objResource($XPress);
			$obj->create();
			$obj->setName($desc,false); // defer saving
			$obj->setUrl($url);
			
			// Associate it w/ the desired object
			switch ($objType){
				case 'Biomarker':
					$obj->link("Biomarkers",$objId,"Resources");
					echo "{\"AjaxMessage\": { "
							.	" \"Description\": \"The ids and types of the newly linked objects\","
							.	" \"Content\": {"
							.		" \"obj1Id\":\"{$obj->getObjId()}\","
							.		" \"obj1Type\":\"{$obj->_getType()}\","
							.		" \"obj2Id\":\"{$objId}\","
							.		" \"obj2Type\":\"{$objType}\""
							.		"}"
							.	"}}";
					//echo "{\"objId\":{$obj->getObjId()},\"containerObjectType\":\"{$objType}\",\"containerObjectId\": {$objId}}";
					break;
				case 'BiomarkerOrganData':
					$obj->link("BiomarkerOrgans",$objId,"Resources");
					echo "{\"AjaxMessage\": { "
							.	" \"Description\": \"The ids and types of the newly linked objects\","
							.	" \"Content\": {"
							.		" \"obj1Id\":\"{$obj->getObjId()}\","
							.		" \"obj1Type\":\"{$obj->_getType()}\","
							.		" \"obj2Id\":\"{$objId}\","
							.		" \"obj2Type\":\"{$objType}\""
							.		"}"
							.	"}}";
					//echo "{\"objId\":{$obj->getObjId()},\"containerObjectType\":\"{$objType}\",\"containerObjectId\": {$objId}}";
					break;
				case 'BiomarkerStudyData':
					$obj->link("BiomarkerStudies",$objId,"Resources");
					echo "{\"AjaxMessage\": { "
							.	" \"Description\": \"The ids and types of the newly linked objects\","
							.	" \"Content\": {"
							.		" \"obj1Id\":\"{$obj->getObjId()}\","
							.		" \"obj1Type\":\"{$obj->_getType()}\","
							.		" \"obj2Id\":\"{$objId}\","
							.		" \"obj2Type\":\"{$objType}\""
							.		"}"
							.	"}}";
					//echo "{\"objId\":{$obj->getObjId()},\"containerObjectType\":\"{$objType}\",\"containerObjectId\": {$objId}}";
					break;
				case 'BiomarkerOrganStudyData':
					$obj->link("BiomarkerOrganStudies",$objId,"Resources");
					echo "{\"AjaxMessage\": { "
							.	" \"Description\": \"The ids and types of the newly linked objects\","
							.	" \"Content\": {"
							.		" \"obj1Id\":\"{$obj->getObjId()}\","
							.		" \"obj1Type\":\"{$obj->_getType()}\","
							.		" \"obj2Id\":\"{$objId}\","
							.		" \"obj2Type\":\"{$objType}\""
							.		"}"
							.	"}}";
					//echo "{\"objId\":{$obj->getObjId()},\"containerObjectType\":\"{$objType}\",\"containerObjectId\": {$objId}}";
					break;
				case 'Study':
					$obj->link("Studies",$objId,"Resources");
					echo "{\"AjaxMessage\": { "
							.	" \"Description\": \"The ids and types of the newly linked objects\","
							.	" \"Content\": {"
							.		" \"obj1Id\":\"{$obj->getObjId()}\","
							.		" \"obj1Type\":\"{$obj->_getType()}\","
							.		" \"obj2Id\":\"{$objId}\","
							.		" \"obj2Type\":\"{$objType}\""
							.		"}"
							.	"}}";
					//echo "{\"objId\":{$obj->getObjId()},\"containerObjectType\":\"{$objType}\",\"containerObjectId\": {$objId}}";
					break;
				default:
					break;
			}
			exit();
	}
	if ($_POST['action'] == 'removeResource'){
		// Get Post Variables
		$objId = $_POST['objId'];
		$containerId = $_POST['containerObjectId'];
		$containerType = $_POST['containerObjectType'];
		
		//Build the object itself
		$obj = new objResource($XPress,$objId);
		// Delete the object
		$obj->delete();
		echo "OK";
		exit();
	}
?>