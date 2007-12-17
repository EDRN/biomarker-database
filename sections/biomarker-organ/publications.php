<?php
	require_once("utilities/common/AjaxEditors.class.php");
	require_once("util/ifcomponents.php");

	$associateDisplay = Publications::displayAssociate("BiomarkerOrganData",$object->getObjId(),"overviewContainer");

	$pubDisplay = '';
	if (sizeof($object->getPublications()) == 0){
		$pubDisplay = 'There is no publication data associated with this biomarker/organ.';
	} else {
		foreach ($object->getPublications() as $pd){
			$pubDisplay .= Publications::displaySummary($pd,$object);
		}
	}
	
	echo <<<END
		{$associateDisplay}
		
		<div id="notice"></div>
		<h4>Associated Publications:</h4>
		<div class="overviewContainer" id="overviewContainer">
			{$pubDisplay}	
		</div>

		<div class="specialactions">
			<ul>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('pubAssociationContainer{$object->getObjId()}');">Associate a publication</span></li>
			</ul>
		</div>
END;

