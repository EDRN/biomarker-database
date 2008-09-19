<?php
class BiomarkerName extends AppModel {
	
	public $belongsTo = array(
		/*
		 * A 'BiomarkerName' Object BELONGS TO one 'Biomarker' object
		 */
		'Biomarker' => array (
			'className' => 'Biomarker',
			'foreignKey'=> 'biomarker_id'
		)
	);
}
?>