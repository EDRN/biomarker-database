<?php

class Biomarker extends AppModel
{

	public $hasMany = array(
		/*
		 * A 'Biomarker' object HAS MANY 'OrganData' objects
		 */
		'OrganData' => array(
			'className' => 'OrganData',
			'foreignKey'=> 'biomarker_id',
			'dependent' => true
		),
		'BiomarkerStudyData' => array(
			'className' => 'BiomarkerStudyData',
			'foreignKey'=> 'biomarker_id',
			'dependent' => 'true'
		),
		'BiomarkerResource' => array(
			'className' => 'BiomarkerResource',
			'foreignKey'=> 'biomarker_id',
			'dependent' => true
		),
		'BiomarkerName' => array(
			'className' => 'BiomarkerName',
			'foreignKey'=> 'biomarker_id',
			'dependent' => true
		)
	);
	
	public $hasAndBelongsToMany = array(
	
		/*
		 * A 'Biomarker' object HAS AND BELONGS TO MANY 'Publication' objects
		 */
		'Publication' => array(
			'className' => 'Publication',
			'join_table'=> 'biomarkers_publications',
			'foreignKey'=> 'biomarker_id',
			'associationForeignKey'=>'publication_id',
			'unique'=>true
		),
	
	);
	
	public static function getDefaultName($biomarker) {
		foreach ($biomarker['BiomarkerName'] as $name) {
			if ($name['isPrimary'] == 1) {return $name['name'];}
		}
		return "unknown";
	}
	
	var $actsAs = 'ExtendAssociations';
	
}
?>