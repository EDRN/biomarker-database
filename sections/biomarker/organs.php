<?php
	require_once("util/ifcomponents.php");
	$organDisplay = '';
	if (sizeof($object->getOrganDatas()) == 0){
		$organDisplay = 'There is no organ data associated with this biomarker.';
	} else {
		foreach ($object->getOrganDatas() as $od){
			$organDisplay .= BiomarkerInterface::drawOrganDataSummary($od);
		}
	}
	$associateDisplay = BiomarkerInterface::drawAssociateOrgan($object);

	echo <<<END
		<div class="associationContainer" id="associationContainer" style="display:none;">
			{$associateDisplay}
		</div>
		<div id="notice"></div>
		<h4>Associated Organs:</h4>
		<div class="overviewContainer" id="overviewContainer">
			{$organDisplay}	
		</div>
	

		<div class="specialactions">
			<ul>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('associationContainer');">Associate organ data</span></li>
			</ul>
		</div>
END;
?>