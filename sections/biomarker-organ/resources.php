<?php
	require_once("utilities/common/AjaxEditors.class.php");
	require_once("util/ifcomponents.php");
	$associateDisplay = Resources::displayAssociate($object->_getType(),$object->getObjId(),"overviewContainer");

	$resDisplay = '';
	if (sizeof($object->getResources()) == 0){
		$resDisplay = 'There are no resources associated with this biomarker/organ.';
	} else {
		foreach ($object->getResources() as $res){
			$resDisplay .= Resources::displaySummary($res,$object);
		}
	}
	
	echo <<<END
			{$associateDisplay}
		<div id="notice"></div>
		<h4>Associated Resources:</h4>
		<div class="overviewContainer" id="overviewContainer">
			{$resDisplay}	
		</div>

		<div class="specialactions">
			<ul>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('resAssociationContainer{$object->getObjId()}');">Associate a resource</span</li>
			</ul>
		</div>
END;

?>