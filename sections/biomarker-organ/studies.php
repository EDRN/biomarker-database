<?php
	require_once("utilities/common/AjaxEditors.class.php");
	require_once("util/ifcomponents.php");

	$associateDisplay = <<<ENDASSOCDISPLAY
			<h3>Add study data to {$object->getBiomarker()->getTitle()}/{$object->getOrgan()->getName()}</h3>
			<span class="hint">Type slowly into the box. Available matches will appear in a drop-down list below.</span><br/><br/>
			Study Title:&nbsp;
				<script type="text/javascript">ajaxti('assocStudy','',455,'model/AjaxAutocomplete.php','Study','Title','assets/images/working.gif','assocStudy','Title');</script>
				<input type="button" value="Associate" onclick="BiomarkerOrganStudyData.Create($('assocStudyAutocompleteID').getValue(),{$object->getObjId()},new AjaxNotify.Create('overviewContainer','BiomarkerOrganStudySummary'));"/>
				<input type="button" value="Cancel" onclick="$('assocStudy_autocomplete').value='';Effect.SwitchOff('associationContainer');"/>
ENDASSOCDISPLAY;


	$studyDisplay = '';
	if (sizeof($object->getStudyDatas()) == 0){
		$studyDisplay = 'There is no study data associated with this biomarker-organ.';
	} else {
		foreach ($object->getStudyDatas() as $sd){
			$studyDisplay .= Studies::displaySummary($sd,"BiomarkerOrgan");
		}
	}


	echo <<<END
		<div class="associationContainer" id="associationContainer" style="display:none;">
			{$associateDisplay}
		</div>
		<div id="notice"></div>
		<h4>Associated Studies:</h4>
		<div class="overviewContainer" id="overviewContainer">
			{$studyDisplay}	
		</div>
	
		<div class="specialactions">
			<ul>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('associationContainer');">Associate study data</span></li>
			</ul>
		</div>
END;

?>