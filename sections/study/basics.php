<?php
	require_once("utilities/common/AjaxEditors.class.php");
	$titleEditor = AjaxEditors::create('model/AjaxHandler.php',$object->getTitle(),"title","title",$object->_getType(),$object->getObjId(),StudyVars::STU_TITLE);
	$popcharEditor = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getBiomarkerPopulationCharacteristics(),"bpc","bpc",$object->_getType(),$object->getObjId(),StudyVars::STU_BIOMARKERPOPULATIONCHARACTERISTICS,array(array("value"=>"1","label"=>"Case Control"),array("value"=>"2","label"=>"Second Choice"),array("value"=>"3","label"=>"Third Choice")),'','click to select');
	$studydesignEditor = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getDesign(),"design","design",$object->_getType(),$object->getObjId(),StudyVars::STU_DESIGN,array(array("value"=>"Retrospective","label"=>"Retrospective"),array("value"=>"Prospective Analysis","label"=>"Prospective Analysis"),array("value"=>"Cross Sectional","label"=>"Cross Sectional")),'','click to select');
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
			<tr class="even"><td class="label">Biomarker Population Characteristics:</td><td>{$popcharEditor}</td></tr>
			<tr><td class="label">Study Design:</td><td>{$studydesignEditor}</td></tr>
			<tr class="even"><td class="label">Status:</td><td>{$studytypeEditor}</td></tr>
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