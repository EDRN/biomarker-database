<?php
/**
 * 	EDRN Biomarker Database
 *  Curation Webapp
 * 
 *  Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 *  
 *  Copyright (c) 2008, California Institute of Technology. 
 *  ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
 * 
 */
	function generateAjaxSelectBoxJS($id,$options,$objectType,$objectId,$attribute,$handlerURL) {
		$opts = array();
		foreach ($options as $opt){
			$opts[] = "['{$opt}','{$opt}']";
		}
		$ops = "[".implode(",",$opts)."]";
		$collection = "collection: {$ops}, ";
		$val = (empty($initialText) ? $defaultText : $initialText);
		// Figure out <initial> item selection on entering edit mode
		// (subsequent default item selection is done in the callback below) 
		$initialSelection = '';
		foreach ($options as $o){
			if ($val == $o){
				$initialSelection = "value: '{$o}', ";
			}
		}
		
		return "new Ajax.InPlaceCollectionEditor('{$id}', "
			. "'{$handlerURL}', "
			. "{ {$collection} {$initialSelection} "
			. "callback: function(form, value) { "
			. "this.value = value; "
			. "return 'action=update&objType={$objectType}&objID={$objectId}&attr={$attribute}&value='+value;"
			. "},highlightcolor:\"#cccccc\",highlightendcolor:\"#dddddd\"});";
		
	}
?>