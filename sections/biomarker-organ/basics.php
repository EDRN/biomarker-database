<?php
	require_once("utilities/common/AjaxEditors.class.php");
	$sensMin = AjaxEditors::create('model/AjaxHandler.php',$object->getSensitivityMin(),"sensmin","sensmin",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_SENSITIVITYMIN);
	$sensMax = AjaxEditors::create('model/AjaxHandler.php',$object->getSensitivityMax(),"sensmax","sensmax",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_SENSITIVITYMAX);
	$specMin = AjaxEditors::create('model/AjaxHandler.php',$object->getSpecificityMin(),"specmin","specmin",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_SPECIFICITYMIN);
	$specMax = AjaxEditors::create('model/AjaxHandler.php',$object->getSpecificityMax(),"specmax","specmax",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_SPECIFICITYMAX);
	$NPVMin  = AjaxEditors::create('model/AjaxHandler.php',$object->getNPVMin(),"npvmin","npvmin",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_NPVMIN);
	$NPVMax  = AjaxEditors::create('model/AjaxHandler.php',$object->getNPVMax(),"npvmax","npvmax",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_NPVMAX);
	$PPVMin  = AjaxEditors::create('model/AjaxHandler.php',$object->getPPVMin(),"ppvmin","ppvmin",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_PPVMIN);
	$PPVMax  = AjaxEditors::create('model/AjaxHandler.php',$object->getPPVMax(),"ppvmax","ppvmax",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_PPVMAX);
	$sensComment = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getSensitivityComment(),"senscomment","senscomment",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_SENSITIVITYCOMMENT,3,47);
	$specComment = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getSpecificityComment(),"speccomment","speccomment",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_SPECIFICITYCOMMENT,3,47);
	$npvComment  = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getNPVComment(),"npvcomment","npvcomment",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_NPVCOMMENT,3,47);
	$ppvComment  = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getPPVComment(),"ppvcomment","ppvcomment",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_PPVCOMMENT,3,47);	
	//$phase = AjaxEditors::create('model/AjaxHandler.php',$object->getPhase(),"phase","phase",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_PHASE,3,47);
	$phase = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getPhase(),"phase","phase",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_PHASE,array("1"=>"One (1)","2"=>"Two (2)","3"=>"Three (3)","4"=>"Four (4)","5"=>"Five (5)"),'','click to select');
	$qaState = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getQAState(),"qastate","qastate",$object->_getType(),$object->getObjId(),BiomarkerOrganDataVars::BIO_QASTATE,array("1"=>"New","2"=>"Under Review","3"=>"Approved","4"=>"Rejected"),'','click to select');
	$Description  = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getDescription(),"description","description",$object->_getType(),$object->getDescription(),BiomarkerOrganDataVars::BIO_DESCRIPTION,3,47);	
	
	echo <<<ENDDISPLAY
		<h4>Description</h4>
		<table class="ajaxEdits">
			<tr class="even"><td class="label">Description:</td><td>{$Description}</td></tr>
		</table>
		<h4>Phase &amp; QA State</h4>
		<table class="ajaxEdits">
			<tr class="even"><td class="label">Biomarker Phase:</td><td>{$phase}</td></tr>
			<tr><td class="label">QA State:</td><td>{$qaState}</td</tr>
		</table>

		<h4>Sensitivity</h4>
		<table class="ajaxEdits">
			<tr class="even"><td class="label">Sensitivity Min (%):</td><td>{$sensMin}</td></tr>
			<tr><td class="label">Sensitivity Max (%):</td><td>{$sensMax}</td></tr>
			<tr class="even"><td class="label">Sensitivity Comment:</td><td>{$sensComment}</td></tr>
		</table>

		<h4>Specificity</h4>
		<table class="ajaxEdits">
			<tr><td class="label">Specificity Min (%):</td><td>{$specMin}</td></tr>
			<tr class="even"><td class="label">Specificity Max (%):</td><td>{$specMax}</td></tr>
			<tr><td class="label">Specificity Comment:</td><td>{$specComment}</td></tr>
		</table>

		<h4>Negative Predictive Value</h4>
		<table class="ajaxEdits">
			<tr class="even"><td class="label">NPV Min (%):</td><td>{$NPVMin}</td></tr>
			<tr><td class="label">NPV Max (%):</td><td>{$NPVMax}</td></tr>
			<tr class="even"><td class="label">NPV Comment:</td><td>{$npvComment}</td></tr>
		</table>

		<h4>Positive Predictive Value</h4>
		<table class="ajaxEdits">
			<tr><td class="label">PPV Min (%):</td><td>{$PPVMin}</td></tr>
			<tr class="even"><td class="label">PPV Max (%):</td><td>{$PPVMax}</td></tr>
			<tr><td class="label">PPV Comment:</td><td>{$ppvComment}</td></tr>
		</table>

ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			</ul>
		</div>
ENDACTIONS
?>