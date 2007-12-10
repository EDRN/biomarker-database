<?php
	require_once("utilities/common/AjaxEditors.class.php");


	$biomarkerDisplay = '';
	$b = $object->getBiomarker();

	$studyDisplay .=  <<<ENDORGANDISPLAY
		<div class="overview">
			<h3><a href="biomarker.php?view=basics&objId={$b->getObjId()}">{$b->getTitle()}</a>&nbsp;</h3>
			&nbsp;&nbsp;&nbsp;Click on the header to view details about the biomarker associated with this Biomarker/Organ pair
		</div>
ENDORGANDISPLAY;
	

	echo <<<ENDDISPLAY
		<div id="notice"></div>
		<h4>The Biomarker:</h4>
		<div class="overviewContainer">
			{$studyDisplay}	
		</div>
	
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul></ul>
		</div>
ENDACTIONS

?>