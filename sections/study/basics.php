<?php
	require_once("utilities/common/AjaxEditors.class.php");
	$titleEditor = AjaxEditors::create('model/AjaxHandler.php',$object->getTitle(),"title","title",$object->_getType(),$object->getObjId(),StudyVars::STU_TITLE);
	$popcharEditor = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getBiomarkerPopulationCharacteristics(),"bpc","bpc",$object->_getType(),$object->getObjId(),StudyVars::STU_BIOMARKERPOPULATIONCHARACTERISTICS,array(array("value"=>"1","label"=>"Case/Control"),array("value"=>"2","label"=>"Longitudinal"),array("value"=>"3","label"=>"Randomized")),'','click to select');
	$popcharDescEditor = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getBPCDescription(),"bpcdesc","bpcdesc",$object->_getType(),$object->getObjId(),StudyVars::STU_BPCDESCRIPTION,4,47,'','click to edit');
	$studydesignEditor = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getDesign(),"design","design",$object->_getType(),$object->getObjId(),StudyVars::STU_DESIGN,array(array("value"=>"Retrospective","label"=>"Retrospective"),array("value"=>"Prospective Analysis","label"=>"Prospective Analysis"),array("value"=>"Cross Sectional","label"=>"Cross Sectional")),'','click to select');
	$studydesignDescEditor = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getDesignDescription(),"ddesc","ddesc",$object->_getType(),$object->getObjId(),StudyVars::STU_DESIGNDESCRIPTION,4,47,'','click to edit');
	$studytypeEditor = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getBiomarkerStudyType(),"studytype","studytype",$object->_getType(),$object->getObjId(),StudyVars::STU_BIOMARKERSTUDYTYPE,array(array("value"=>"Unregistered","label"=>"Unregistered"),array("value"=>"Registered","label"=>"Registered")),'','click to select');
	$abstractEditor = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getAbstract(),"abstract","abstract",$object->_getType(),$object->getObjId(),StudyVars::STU_ABSTRACT,7,47,'','click to edit');
	
	echo <<<ENDDELETEDISPLAY
		<div class="associationContainer warn delete" id="deleteStu" style="display:none;">
			<h3>Confirm Delete</h3>
			Are you sure you want to delete this Study? This action can not be undone.<br/><br/>
			<form action="study.php" method="post">
			<input type="hidden" name="special" value="delete"/>
			<input type="hidden" name="objId" value="{$object->getObjId()}"/>
			<input type="submit" value="Delete"/>&nbsp;
			<span class="pseudolink" onclick="Element.hide('deleteStu');">Cancel</span>
		</div>
ENDDELETEDISPLAY;
	
	echo <<<ENDDISPLAY
		<h4>Basic Information</h4>
		<table class="ajaxEdits">
			<tr><td class="label">Title:</td><td>{$titleEditor}</td></tr>
			<tr class="even"><td class="label">Status:</td><td>{$studytypeEditor}</td></tr>
		</table>
		<h4>Biomarker Population Characteristics</h4>
		<table class="ajaxEdits">
			<tr><td class="label">Category:</td><td>{$popcharEditor}</td></tr>
			<tr class="even"><td class="label">Description:</td><td>{$popcharDescEditor}</td></tr>
		</table>
		<h4>Study Design</h4>
		<table class="ajaxEdits">
			<tr><td class="label">Study Design:</td><td>{$studydesignEditor}</td></tr>
			<tr class="even"><td class="label">Description:</td><td>{$studydesignDescEditor}</td></tr>
		</table>
		<h4>Abstract</h4>
		<table class="ajaxEdits">
			<tr><td class="label">Abstract:</td><td>{$abstractEditor}</td></tr>
		</table>

ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			  <li><a href="browse/studies/">Browse Studies</a></li>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('deleteStu');">Delete This Study</span></li>
			</ul>
		</div>
ENDACTIONS
?>