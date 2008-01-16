<?php
	require_once("utilities/common/AjaxEditors.class.php");
	$titleEditor = AjaxEditors::create('model/AjaxHandler.php',$object->getTitle(),"title","title",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_TITLE);
	$shortNameEd = AjaxEditors::create('model/AjaxHandler.php',$object->getShortName(),"shortname","shortname",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_SHORTNAME);
	$urnEditor   = AjaxEditors::create('model/AjaxHandler.php',$object->getBiomarkerID(),"ident","ident",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_BIOMARKERID);
	$phaseEditor = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getPhase(),"phase","phase",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_PHASE,array(array("value"=>"1","label"=>"One (I)"),array("value"=>"2","label"=>"Two (II)"),array("value"=>"3","label"=>"Three (III)"),array("value"=>"4","label"=>"Four (IV)"),array("value"=>"5","label"=>"Five (V)")),'','click to select');
	$qaStateEditor  = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getQAState(),"qastate","qastate",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_QASTATE,array(array("value"=>"1","label"=>"New"),array("value"=>"2","label"=>"Under Review"),array("value"=>"3","label"=>"Approved"),array("value"=>"4","label"=>"Rejected")),'','click to select');
	$securityEditor = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getSecurity(),"security","security",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_SECURITY,array(array("value"=>"1","label"=>"Public"),array("value"=>"2","label"=>"Private")),'',"click to select");
	$typeEditor  = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getType(),"type","type",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_TYPE,array(array("value"=>"1","label"=>"Epigenetic"),array("value"=>"2","label"=>"Gene"),array("value"=>"3","label"=>"Protein")),'',"click to select");
	$descriptionEditor = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getDescription(),"description","description",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_DESCRIPTION,7,47,'','click to edit');
	
	echo  <<<ENDDELETEDISPLAY
		<div class="associationContainer warn delete" id="deleteBio" style="display:none;">
			<h3>Confirm Delete</h3>
			Are you sure you want to delete this Biomarker? This action can not be undone.<br/><br/>
			<form action="biomarker.php" method="post">
			<input type="hidden" name="special" value="delete"/>
			<input type="hidden" name="objId" value="{$object->getObjId()}"/>
			<input type="submit" value="Delete"/>&nbsp;
			<span class="pseudolink" onclick="Element.hide('deleteBio');">Cancel</span>
		</div>
ENDDELETEDISPLAY;
	
	echo <<<ENDDISPLAY
		<h4>Basic Information:</h4>
		<table class="ajaxEdits">
			<tr class="even"><td class="label">Title:</td><td>{$titleEditor}</td></tr>
			<tr><td class="label">Short Name:</td><td>{$shortNameEd}</td></tr>
			<tr class="even"><td class="label">Identifier:</td><td>{$urnEditor}</td></tr>
			<tr><td class="label">Phase:</td><td>{$phaseEditor}</td></tr>
			<tr class="even"><td class="label">Security:</td><td>{$securityEditor}</td></tr>
			<tr><td class="label">QA State:</td><td>{$qaStateEditor}</td></tr>
			<tr class="even"><td class="label">Type:</td><td>{$typeEditor}</td></tr>
			<tr><td class="label">Description:</td><td>{$descriptionEditor}</td></tr>
		</table>
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			  <li><a href="browse/biomarkers/">Browse Biomarkers</a></li>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('deleteBio');">Delete This Biomarker</span></li>
			</ul>
		</div>
ENDACTIONS
?>